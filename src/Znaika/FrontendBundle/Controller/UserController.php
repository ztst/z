<?

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\Form\Form;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Core\SecurityContext;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Entity\Profile\PasswordRecovery;
    use Znaika\FrontendBundle\Entity\Profile\UserRegistration;
    use Znaika\FrontendBundle\Form\Model\Registration;
    use Znaika\FrontendBundle\Form\Type\RegistrationType;
    use Znaika\FrontendBundle\Form\User\PasswordRecoveryType;
    use Znaika\FrontendBundle\Form\User\UserPhotoType;
    use Znaika\FrontendBundle\Form\User\UserProfileType;
    use Znaika\FrontendBundle\Helper\Encode\RegisterKeyEncoder;
    use Znaika\FrontendBundle\Helper\Odnoklassniki;
    use Znaika\FrontendBundle\Helper\Security\UserAuthenticator;
    use Znaika\FrontendBundle\Helper\Uploader\UserPhotoUploader;
    use Znaika\FrontendBundle\Helper\UserOperation\UserOperationListener;
    use Znaika\FrontendBundle\Helper\Util\FileSystem\UnixSystemUtils;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserStatus;
    use Znaika\FrontendBundle\Helper\Util\SocialNetworkUtil;
    use Znaika\FrontendBundle\Helper\Util\UserAgentInfoProvider;
    use Znaika\FrontendBundle\Helper\Vkontakte;
    use Znaika\FrontendBundle\Repository\Profile\Badge\UserBadgeRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRegistrationRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;
    use Znaika\FrontendBundle\Repository\Profile\PasswordRecoveryRepository;
    use Znaika\FrontendBundle\Form\User\GenerateNewPasswordType;

    class UserController extends Controller
    {
        public function odnoklassnikiLoginAction(Request $request)
        {
            /** @var Odnoklassniki $odnoklassniki */
            $odnoklassniki = $this->get('znaika_frontend.odnoklassniki');
            $odnoklassniki->setRedirectUrl($this->container->getParameter('odnoklassniki_redirect_uri'));
            $code = $request->get("code");
            if (empty($code))
            {
                $this->addReferrerToSession();

                return new RedirectResponse($odnoklassniki->getLoginUrl());
            }
            $odnoklassniki->getToken($code);

            $userInfo        = $odnoklassniki->api('users.getCurrentUser');
            $odnoklassnikiId = $userInfo['uid'] ? $userInfo['uid'] : 0;
            $user            = $this->getUserRepository()->getOneByOdnoklassnikiId($odnoklassnikiId);
            $socialType      = SocialNetworkUtil::ODNOKLASSNIKI;

            return $this->processUserSocialAuth($user, $userInfo, $socialType);
        }

        public function facebookLoginAction()
        {
            /** @var \Facebook $facebook */
            $facebook = $this->get('znaika_frontend.facebook');

            $facebookId = $facebook->getUser();
            if (!$facebookId)
            {
                $this->addReferrerToSession();

                $params   = array(
                    'scope'        => 'read_stream, friends_likes',
                    'redirect_uri' => $this->container->getParameter('facebook_redirect_uri'),
                );
                $loginUrl = $facebook->getLoginUrl($params);

                return new RedirectResponse($loginUrl);
            }

            $userInfo   = $facebook->api('/me');
            $user       = $this->getUserRepository()->getOneByFacebookId($facebookId);
            $socialType = SocialNetworkUtil::FACEBOOK;

            return $this->processUserSocialAuth($user, $userInfo, $socialType);
        }

        public function vkLoginAction(Request $request)
        {
            /** @var Vkontakte $vkontakte */
            $vkontakte = $this->get('znaika_frontend.vkontakte');

            $code = $request->get("code");
            if (empty($code))
            {
                $this->addReferrerToSession();

                return new RedirectResponse($vkontakte->getLoginUrl());
            }

            $userInfo   = $vkontakte->getUserInfo($code);
            $vkId       = $userInfo['uid'] ? $userInfo['uid'] : 0;
            $user       = $this->getUserRepository()->getOneByVkId($vkId);
            $socialType = SocialNetworkUtil::VK;

            return $this->processUserSocialAuth($user, $userInfo, $socialType);
        }

        public function hasViewedBadgesAction()
        {
            $user            = $this->getUser();
            $badgeRepository = $this->getUserBadgeRepository();
            $badges          = $badgeRepository->getUserNotViewedBadges($user);
            foreach ($badges as $badge)
            {
                $badge->setIsViewed(true);
                $badgeRepository->save($badge);
            }

            $success = true;

            return new JsonResponse(array('success' => $success));
        }

        public function loginAction(Request $request)
        {
            $session = $request->getSession();

            // get the login error if there is one
            if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
            {
                $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
            }
            else
            {
                $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
                $session->remove(SecurityContext::AUTHENTICATION_ERROR);
            }

            $userRepository             = $this->getUserRepository();
            $registerForm               = $this->createForm(new RegistrationType($userRepository), new Registration());
            $passwordRecoveryForm       = $this->createForm(new PasswordRecoveryType($userRepository),
                new PasswordRecovery());

            $referrer      = $request->headers->get('referer');
            $loginTemplate = ($request->isXmlHttpRequest()) ? 'ZnaikaFrontendBundle:User:loginAjax.html.twig' : 'ZnaikaFrontendBundle:User:login.html.twig';

            return $this->render($loginTemplate,
                array(
                    // last username entered by the user
                    'last_username'        => $session->get(SecurityContext::LAST_USERNAME),
                    'error'                => $error,
                    'referrer'             => $referrer,
                    'registerForm'         => $registerForm->createView(),
                    'passwordRecoveryForm' => $passwordRecoveryForm->createView()
                )
            );
        }

        /**
         * @param Request $request
         *
         * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
         */
        public function passwordRecoveryAction(Request $request)
        {
            $passwordRecovery = new PasswordRecovery();
            $userRepository   = $this->getUserRepository();

            $form = $this->createForm(new PasswordRecoveryType($userRepository), $passwordRecovery);
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $response = $this->getPasswordRecoverySuccessResponse($passwordRecovery, $form);
            }
            else
            {
                $response = $this->getPasswordRecoveryFailedResponse($form);
            }

            return $response;
        }

        /**
         * @param $recoveryKey
         *
         * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
         * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
         */
        public function generateNewPasswordAction($recoveryKey)
        {
            $passwordRecoveryRepository = $this->getPasswordRecoveryRepository();
            $passwordRecovery           = $passwordRecoveryRepository->getOneByPasswordRecoveryKey($recoveryKey);

            if (!$passwordRecovery)
            {
                throw $this->createNotFoundException("Not found password recovery");
            }

            $expiredTime = $passwordRecovery->getRecoveryTime()->add(new \DateInterval(PasswordRecovery::EXPIRED_TIME));
            $currentTime = new \DateTime("now");
            if ($currentTime > $expiredTime)
            {
                $this->recoverUserPassword($passwordRecovery->getUser());

                return $this->render('ZnaikaFrontendBundle:User:expiredRecoveryKey.html.twig');
            }

            $user = $passwordRecovery->getUser();
            $form = $this->createForm(new GenerateNewPasswordType(), $user);

            $request = $this->getRequest();
            $form->handleRequest($request);

            if ($request->isMethod("POST") && $form->isValid())
            {
                $response = $this->getGenerateNewPasswordSuccessResponse($user);
                $passwordRecoveryRepository->delete($passwordRecovery);
            }
            else
            {
                $response = $this->render('ZnaikaFrontendBundle:User:getNewPassword.html.twig', array(
                    'form'        => $form->createView(),
                    'recoveryKey' => $recoveryKey
                ));
            }

            return $response;
        }

        public function registerConfirmAction($registerKey)
        {
            $userRegistrationRepository = $this->getUserRegistrationRepository();
            $userRegistration           = $userRegistrationRepository->getOneByRegisterKey($registerKey);

            if (!$userRegistration)
            {
                throw $this->createNotFoundException("User Registration not found");
            }

            $user        = $userRegistration->getUser();
            $expiredTime = $userRegistration->getCreatedTime()->add(new \DateInterval(UserRegistration::EXPIRED_TIME));
            $currentTime = new \DateTime("now");
            if ($currentTime > $expiredTime)
            {
                $this->registerUser($user);

                return $this->render('ZnaikaFrontendBundle:User:expiredRegistrationKey.html.twig');
            }

            $user->setStatus(UserStatus::ACTIVE);

            $userRegistrationRepository->delete($userRegistration);

            $userAuthenticator = $this->getUserAuthenticator();
            $userAuthenticator->authenticate($user);

            $listener = $this->getUserOperationListener();
            $listener->onRegistration($user);

            $this->get('session')->getFlashBag()->add(
                 'notice',
                     $this->get('translator')->trans('congratulations_registration_success')
            );

            return new RedirectResponse($this->generateUrl('show_user_profile', array('userId' => $user->getUserId())));
        }

        public function registerAction(Request $request)
        {
            $registration         = new Registration();
            $userRepository       = $this->getUserRepository();
            $autoGeneratePassword = $request->get("autoGeneratePassword", false);

            $form = $this->createForm(new RegistrationType($userRepository, $autoGeneratePassword), $registration);
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $response = $this->getRegisterSuccessResponse($registration, $form);
            }
            else
            {
                $response = $this->getRegisterFailedResponse($form, $autoGeneratePassword);
            }

            return $response;
        }

        public function showUserProfileAction($userId)
        {
            $currentUser = $this->getUser();
            $ownProfile  = ($currentUser && $currentUser->getUserId() == $userId);

            if ($ownProfile)
            {
                return new RedirectResponse($this->generateUrl('edit_user_profile', array(
                    'userId' => $userId
                )));
            }

            $userRepository = $this->getUserRepository();
            $user           = $userRepository->getOneByUserId($userId);

            if (!$user)
            {
                return $this->createNotFoundException("User not found");
            }

            return $this->render('ZnaikaFrontendBundle:User:showUserProfile.html.twig', array(
                'user' => $user,
            ));
        }


        public function editUserProfileAction(Request $request)
        {
            $userId      = $request->get('userId');
            $currentUser = $this->getUser();
            $canEdit     = ($currentUser && $currentUser->getUserId() == $userId);

            if (!$canEdit)
            {
                throw $this->createNotFoundException("Can't manage user");
            }

            $userRepository = $this->getUserRepository();
            $user           = $currentUser;

            $profileForm = $this->handleProfileForm($user, $userRepository);

            $userPhotoForm = $this->createForm(new UserPhotoType(), $user);

            return $this->render('ZnaikaFrontendBundle:User:editUserProfile.html.twig', array(
                'profileForm'   => $profileForm->createView(),
                'userPhotoForm' => $userPhotoForm->createView(),
                'user'          => $user,
                'userId'        => $userId,
            ));
        }

        public function editUserPhotoAction(Request $request)
        {
            $userId      = $request->get('userId');
            $currentUser = $this->getUser();
            $canEdit     = ($currentUser && $currentUser->getUserId() == $userId);

            if (!$canEdit)
            {
                throw $this->createNotFoundException("Can't manage user");
            }

            $userRepository = $this->getUserRepository();
            $user           = $currentUser;

            $userPhotoForm = $this->createForm(new UserPhotoType(), $user);
            $userPhotoForm->handleRequest($request);
            $success = false;
            if ($userPhotoForm->isValid())
            {
                $userPhotoUploader = $this->getUserPhotoUploader();
                $userPhotoUploader->upload($user);
                $userRepository->save($user);
                $success = true;
            }

            $data       = array(
                "photoUrl" => $user->getPhotoUrl() . "?" . time(),
                "success"  => $success
            );
            $uaProvider = new UserAgentInfoProvider();
            $uaProvider->parse($request->headers->get('User-Agent'));

            return $uaProvider->getUaFamily() == "IE" ? new Response(json_encode($data)) : JsonResponse::create($data);
        }

        public function deleteUserPhotoAction($userId)
        {
            /** @var User $currentUser */
            $currentUser = $this->getUser();
            $canEdit     = ($currentUser && $currentUser->getUserId() == $userId);

            if (!$canEdit)
            {
                throw $this->createNotFoundException("Can't manage user");
            }

            $userRepository = $this->getUserRepository();
            $user = $currentUser;
            $user->setHasPhoto(false);
            $userRepository->save($user);

            $userPhotoUploader = $this->getUserPhotoUploader();
            $filePath = $userPhotoUploader->getFilePath($user);
            UnixSystemUtils::remove($filePath);

            return JsonResponse::create(array("success" => true));
        }

        /**
         * @return UserOperationListener
         */
        private function getUserOperationListener()
        {
            return $this->get('znaika_frontend.user_operation_listener');
        }

        /**
         * @return UserBadgeRepository
         */
        private function getUserBadgeRepository()
        {
            return $this->get('znaika_frontend.user_badge_repository');
        }

        private function registerUser(User $user)
        {
            $request = $this->getRequest();
            $this->setUserNickname($user);
            $this->initFromSocialNetworkInfo($user);
            $user->setCreatedTime(new \DateTime());
            $user->setStatus(UserStatus::REGISTERED);

            $factory         = $this->get('security.encoder_factory');
            $encoder         = $factory->getEncoder($user);
            $password        = $user->getPassword();
            $encodedPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($encodedPassword);

            $userRepository = $this->getUserRepository();
            $userRepository->save($user);

            $userRegistration = $this->createUserRegistration($user);

            if ($request->get("autoGeneratePassword", false))
            {
                $this->get('znaika_frontend.user_mailer')
                     ->sendRegisterWithPasswordGenerateConfirm($userRegistration, $password);
            }
            else
            {
                $this->get('znaika_frontend.user_mailer')->sendRegisterConfirm($userRegistration);
            }
        }

        private function initFromSocialNetworkInfo($user)
        {
            $request = $this->getRequest();
            $session = $request->getSession();

            $socialUserInfo = $session->get("socialUserInfo", null);
            $socialType     = $session->get("socialType", null);
            $session->remove("socialUserId");
            $session->remove("socialType");

            SocialNetworkUtil::addUserSocialInfo($user, $socialType, $socialUserInfo);
        }

        private function createUserRegistration(User $user)
        {
            $userRegistration = $user->getLastUserRegistration();

            /** @var RegisterKeyEncoder $encoder */
            $encoder = $this->get('znaika_frontend.register_key_encoder');
            $key     = $encoder->encode($user->getEmail(), $user->getSalt());
            $userRegistration->setRegisterKey($key);
            $userRegistration->setCreatedTime(new \DateTime());

            $userRegistrationRepository = $this->getUserRegistrationRepository();
            $userRegistrationRepository->save($userRegistration);

            return $userRegistration;
        }

        /**
         * @return UserRepository
         */
        private function getUserRepository()
        {
            $userRepository = $this->get('znaika_frontend.user_repository');

            return $userRepository;
        }

        /**
         * @return UserAuthenticator
         */
        private function getUserAuthenticator()
        {
            $userAuthenticator = $this->get('znaika_frontend.user_authenticator');

            return $userAuthenticator;
        }

        private function addReferrerToSession()
        {
            $request = $this->getRequest();
            $session = $request->getSession();
            $referer = $request->headers->get('referer');
            $session->set('referer', $referer);
        }

        /**
         * @param User $user
         */
        private function setUserNickname(User $user)
        {
            $nickname = preg_replace("/@(.*)$/", "", $user->getEmail());
            $user->setNickname($nickname);
        }

        /**
         * @param $user
         * @param $userInfo
         * @param $socialType
         *
         * @return RedirectResponse
         */
        private function processUserSocialAuth($user, $userInfo, $socialType)
        {
            $request = $this->getRequest();
            $session = $request->getSession();
            $referer = $session->get('referer', $this->generateUrl('znaika_frontend_homepage'));

            if ($user)
            {
                $userAuthenticator = $this->getUserAuthenticator();
                $userAuthenticator->authenticate($user);
            }
            else
            {
                $session->set("showRegisterSocial", true);
                $session->set("socialUserInfo", $userInfo);
                $session->set("socialType", $socialType);
            }

            return new RedirectResponse($referer);
        }

        /**
         * @param $registration
         * @param $form
         *
         * @internal param \Symfony\Component\HttpFoundation\Request $request
         * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
         */
        private function getRegisterSuccessResponse(Registration $registration, Form $form)
        {
            $request = $this->getRequest();
            $user    = $registration->getUser();
            $this->registerUser($user);

            if ($request->isXmlHttpRequest())
            {
                $html     = $this->renderView('ZnaikaFrontendBundle:User:registration_success_block.html.twig',
                    array(
                        'form' => $form->createView()
                    ));
                $array    = array('success' => true, 'html' => $html);
                $response = new JsonResponse($array);

                return $response;
            }
            else
            {
                $response = $this->render('ZnaikaFrontendBundle:User:registerSuccess.html.twig');

                return $response;
            }
        }

        /**
         * @return UserRegistrationRepository
         */
        private function getUserRegistrationRepository()
        {
            $userRegistrationRepository = $this->get('znaika_frontend.user_registration_repository');

            return $userRegistrationRepository;
        }

        /**
         * @param $form
         * @param $autoGeneratePassword
         *
         * @internal param \Symfony\Component\HttpFoundation\Request $request
         * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
         */
        private function getRegisterFailedResponse(Form $form, $autoGeneratePassword)
        {
            $request = $this->getRequest();
            if ($request->isXmlHttpRequest())
            {
                $html     = $this->renderView('ZnaikaFrontendBundle:User:register_form.html.twig',
                    array(
                        'form'                 => $form->createView(),
                        'autoGeneratePassword' => $autoGeneratePassword,
                    ));
                $array    = array('success' => false, 'html' => $html);
                $response = new JsonResponse($array);

                return $response;
            }
            else
            {
                $response = $this->render('ZnaikaFrontendBundle:User:register.html.twig',
                    array(
                        'form' => $form->createView()
                    ));

                return $response;
            }
        }

        /**
         * @param User $user
         */
        private function recoverUserPassword(User $user)
        {
            $passwordRecovery = $user->getLastPasswordRecovery();

            /** @var RegisterKeyEncoder $encoder */
            $encoder = $this->get('znaika_frontend.register_key_encoder');
            $key     = $encoder->encode($user->getEmail() . time(), $user->getSalt());
            $passwordRecovery->setRecoveryKey($key);

            $passwordRecovery->setRecoveryTime(new \DateTime());
            $passwordRecoveryRepository = $this->get('znaika_frontend.password_recovery_repository');
            $passwordRecoveryRepository->save($passwordRecovery);

            $this->get('znaika_frontend.user_mailer')->sendPasswordRecoveryConfirm($passwordRecovery);
        }

        /**
         * @param $passwordRecovery
         * @param $form
         *
         * @internal param \Symfony\Component\HttpFoundation\Request $request
         * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
         */
        private function getPasswordRecoverySuccessResponse(PasswordRecovery $passwordRecovery, Form $form)
        {
            $request = $this->getRequest();
            $user    = $passwordRecovery->getUser();
            $this->recoverUserPassword($user);

            if ($request->isXmlHttpRequest())
            {
                $html     = $this->renderView('ZnaikaFrontendBundle:User:forget_password_success_block.html.twig',
                    array(
                        'form' => $form->createView()
                    ));
                $array    = array('success' => true, 'html' => $html);
                $response = new JsonResponse($array);

                return $response;
            }
            else
            {
                $response = $this->render('ZnaikaFrontendBundle:User:forgetPasswordSuccess.html.twig');

                return $response;
            }
        }

        /**
         * @param $form
         *
         * @internal param \Symfony\Component\HttpFoundation\Request $request
         * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
         */
        private function getPasswordRecoveryFailedResponse(Form $form)
        {
            $request = $this->getRequest();
            if ($request->isXmlHttpRequest())
            {
                $html     = $this->renderView('ZnaikaFrontendBundle:User:forget_password_form.html.twig',
                    array(
                        'form' => $form->createView()
                    ));
                $array    = array('success' => false, 'html' => $html);
                $response = new JsonResponse($array);

                return $response;
            }
            else
            {
                $response = $this->render('ZnaikaFrontendBundle:User:forget_password.html.twig',
                    array(
                        'form' => $form->createView()
                    ));

                return $response;
            }
        }

        /**
         * @param User $user
         *
         * @return RedirectResponse
         */
        private function getGenerateNewPasswordSuccessResponse(User $user)
        {
            $factory         = $this->get('security.encoder_factory');
            $encoder         = $factory->getEncoder($user);
            $encodedPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($encodedPassword);

            $userRepository = $this->getUserRepository();
            $userRepository->save($user);

            $userAuthenticator = $this->getUserAuthenticator();
            $userAuthenticator->authenticate($user);

            return new RedirectResponse($this->generateUrl('show_user_profile', array('userId' => $user->getUserId())));
        }

        /**
         * @return PasswordRecoveryRepository
         */
        private function getPasswordRecoveryRepository()
        {
            $passwordRecoveryRepository = $this->get('znaika_frontend.password_recovery_repository');

            return $passwordRecoveryRepository;
        }

        /**
         * @param $user
         * @param $userRepository
         *
         * @return \Symfony\Component\Form\Form
         */
        private function handleProfileForm(User $user, UserRepository $userRepository)
        {
            $request = $this->getRequest();
            $form    = $this->createForm(new UserProfileType(), $user);

            if ($request->request->has('znaika_frontendbundle_user_userprofile'))
            {
                $form->handleRequest($request);
                if ($form->isValid())
                {
                    $listener = $this->getUserOperationListener();
                    $listener->onEditProfile($user);

                    $userRepository->save($user);
                }
            }

            return $form;
        }

        /**
         * @return UserPhotoUploader
         */
        private function getUserPhotoUploader()
        {
            $userPhotoUploader = $this->get('znaika_frontend.user_photo_uploader');

            return $userPhotoUploader;
        }
    }
