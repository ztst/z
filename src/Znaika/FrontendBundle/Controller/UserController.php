<?

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\SecurityContext;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Form\Model\Registration;
    use Znaika\FrontendBundle\Form\Type\RegistrationType;
    use Znaika\FrontendBundle\Form\User\UserProfileType;
    use Znaika\FrontendBundle\Helper\Encode\RegisterKeyEncoder;
    use Znaika\FrontendBundle\Helper\Odnoklassniki;
    use Znaika\FrontendBundle\Helper\Security\UserAuthenticator;
    use Znaika\FrontendBundle\Helper\UserOperation\UserOperationListener;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserStatus;
    use Znaika\FrontendBundle\Helper\Util\SocialNetworkUtil;
    use Znaika\FrontendBundle\Helper\Vkontakte;
    use Znaika\FrontendBundle\Repository\Profile\Badge\UserBadgeRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

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

            $userRepository = $this->getUserRepository();
            $registerForm   = $this->createForm(new RegistrationType($userRepository), new Registration());

            $referrer      = $request->headers->get('referer');
            $loginTemplate = ($request->isXmlHttpRequest()) ? 'ZnaikaFrontendBundle:User:loginAjax.html.twig' : 'ZnaikaFrontendBundle:User:login.html.twig';

            return $this->render($loginTemplate,
                array(
                    // last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error'         => $error,
                    'referrer'      => $referrer,
                    'registerForm'  => $registerForm->createView()
                )
            );
        }

        public function registerConfirmAction($registerKey)
        {
            $userRegistrationRepository = $this->get('znaika_frontend.user_registration_repository');
            $userRegistration           = $userRegistrationRepository->getOneByRegisterKey($registerKey);

            if (!$userRegistration)
            {
                throw $this->createNotFoundException("Not found user registration");
            }

            $user = $userRegistration->getUser();
            $user->setStatus(UserStatus::ACTIVE);

            $userRegistrationRepository->delete($userRegistration);

            $userAuthenticator = $this->getUserAuthenticator();
            $userAuthenticator->authenticate($user);

            $listener = $this->getUserOperationListener();
            $listener->onRegistration($user);

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

            $userRepository = $this->getUserRepository();
            $user           = $userRepository->getOneByUserId($userId);

            $form = $this->createForm(new UserProfileType(), $user, array('readonly' => !$ownProfile));

            return $this->render('ZnaikaFrontendBundle:User:showUserProfile.html.twig', array(
                'form'       => $form->createView(),
                'ownProfile' => $ownProfile,
                'userId'     => $userId,
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
            $user           = $userRepository->getOneByUserId($userId);

            $form = $this->createForm(new UserProfileType(), $user, array('readonly' => !$canEdit));

            $form->handleRequest($request);
            if ($form->isValid())
            {
                $listener = $this->getUserOperationListener();
                $listener->onEditProfile($user);

                $userRepository->save($user);
            }

            return new RedirectResponse($this->generateUrl('show_user_profile', array(
                'userId' => $userId
            )));
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

            $userRegistrationRepository = $this->get('znaika_frontend.user_registration_repository');
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
        private function getRegisterSuccessResponse($registration, $form)
        {
            $request = $this->getRequest();
            $user = $registration->getUser();
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
         * @param $form
         * @param $autoGeneratePassword
         *
         * @internal param \Symfony\Component\HttpFoundation\Request $request
         * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
         */
        private function getRegisterFailedResponse($form, $autoGeneratePassword)
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
    }
