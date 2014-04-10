<?php

    namespace Znaika\ProfileBundle\Controller;

    use Symfony\Component\Form\Form;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\Encoder\EncoderFactory;
    use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
    use Symfony\Component\Security\Core\SecurityContext;
    use Znaika\FrontendBundle\Controller\ZnaikaController;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Entity\PasswordRecovery;
    use Znaika\ProfileBundle\Entity\UserRegistration;
    use Znaika\ProfileBundle\Form\Model\Registration;
    use Znaika\ProfileBundle\Form\Type\RegistrationType;
    use Znaika\ProfileBundle\Form\PasswordRecoveryType;
    use Znaika\ProfileBundle\Helper\Encode\RegisterKeyEncoder;
    use Znaika\ProfileBundle\Helper\Mail\UserMailer;
    use Znaika\ProfileBundle\Helper\Odnoklassniki;
    use Znaika\ProfileBundle\Helper\Security\UserAuthenticator;
    use Znaika\ProfileBundle\Helper\Util\UserStatus;
    use Znaika\FrontendBundle\Helper\Util\SocialNetworkUtil;
    use Znaika\ProfileBundle\Helper\Vkontakte;
    use Znaika\ProfileBundle\Repository\UserRegistrationRepository;
    use Znaika\ProfileBundle\Repository\UserRepository;
    use Znaika\ProfileBundle\Repository\PasswordRecoveryRepository;
    use Znaika\ProfileBundle\Form\GenerateNewPasswordType;

    class AuthController extends ZnaikaController
    {
        public function odnoklassnikiLoginAction(Request $request)
        {
            /** @var Odnoklassniki $odnoklassniki */
            $odnoklassniki = $this->get('znaika.odnoklassniki');
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
            $facebook = $this->get('znaika.facebook');

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
            $vkontakte = $this->get('znaika.vkontakte');

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

        public function loginAction(Request $request)
        {
            $session = $request->getSession();

            if (!$request->isXmlHttpRequest())
            {
                return new RedirectResponse($this->generateUrl('znaika_frontend_homepage'));
            }

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

            $referrer             = $request->headers->get('referer');
            $userRepository       = $this->getUserRepository();
            $registerForm         = $this->createForm(new RegistrationType($userRepository), new Registration());
            $passwordRecoveryForm = $this->createForm(new PasswordRecoveryType($userRepository),
                new PasswordRecovery());


            return $this->render('ZnaikaProfileBundle:Default:loginAjax.html.twig',
                array(
                    // last username entered by the user
                    'last_username'        => $session->get(SecurityContext::LAST_USERNAME),
                    'error'                => $error,
                    'referrer'             => $referrer,
                    'registerForm'         => $registerForm->createView(),
                    'passwordRecoveryForm' => $passwordRecoveryForm->createView(),
                    'showRegisterForm'     => $request->get("showRegisterForm", false),
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
            if (!$request->isXmlHttpRequest())
            {
                return new RedirectResponse($this->generateUrl('znaika_frontend_homepage'));
            }

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
         * @param \Symfony\Component\HttpFoundation\Request $request
         *
         * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
         * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
         */
        public function generateNewPasswordAction(Request $request)
        {
            $passwordRecoveryRepository = $this->getPasswordRecoveryRepository();
            $recoveryKey                = $request->get("recoveryKey");
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

                return $this->render('ZnaikaProfileBundle:Default:expiredRecoveryKey.html.twig');
            }

            $user = $passwordRecovery->getUser();
            $form = $this->createForm(new GenerateNewPasswordType(), $user);

            $form->handleRequest($request);

            if ($request->isMethod("POST") && $form->isValid())
            {
                $response = $this->getGenerateNewPasswordSuccessResponse($user);
                $passwordRecoveryRepository->delete($passwordRecovery);
            }
            else
            {
                $response = $this->render('ZnaikaProfileBundle:Default:getNewPassword.html.twig', array(
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

                return $this->render('ZnaikaProfileBundle:Default:expiredRegistrationKey.html.twig');
            }

            $user->setStatus(UserStatus::NOT_VERIFIED);

            $userRegistrationRepository->delete($userRegistration);

            $userAuthenticator = $this->getUserAuthenticator();
            $userAuthenticator->authenticate($user);

            $listener = $this->getUserOperationListener();
            $listener->onRegistration($user);

            $this->get('session')->getFlashBag()
                 ->add('notice', $this->get('translator')->trans('congratulations_registration_success'));

            $this->setIsSocialRegisterComplete($user);

            return new RedirectResponse($this->generateUrl('show_user_profile', array('userId' => $user->getUserId())));
        }

        public function registerAction(Request $request)
        {
            if (!$request->isXmlHttpRequest())
            {
                return new RedirectResponse($this->generateUrl('znaika_frontend_homepage'));
            }

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

        private function getUserOperationListener()
        {
            return $this->get('znaika.user_operation_listener');
        }

        private function registerUser(User $user)
        {
            $request = $this->getRequest();
            $this->setUserNickname($user);
            $this->initFromSocialNetworkInfo($user);
            $user->setCreatedTime(new \DateTime());
            $user->setStatus(UserStatus::REGISTERED);

            $encoder         = $this->getPasswordEncoder($user);
            $password        = $user->getPassword();
            $encodedPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($encodedPassword);

            $userRepository = $this->getUserRepository();
            $userRepository->save($user);

            $userRegistration = $this->createUserRegistration($user);

            $userMailer = $this->getUserMailer();
            if ($request->get("autoGeneratePassword", false))
            {
                $userMailer
                    ->sendRegisterWithPasswordGenerateConfirm($userRegistration, $password);
            }
            else
            {
                $userMailer->sendRegisterConfirm($userRegistration);
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
            $encoder = $this->get('znaika.register_key_encoder');
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
            $userRepository = $this->get('znaika.user_repository');

            return $userRepository;
        }

        /**
         * @return UserAuthenticator
         */
        private function getUserAuthenticator()
        {
            $userAuthenticator = $this->get('znaika.user_authenticator');

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
                $html     = $this->renderView('ZnaikaProfileBundle:Default:registration_success_block.html.twig',
                    array(
                        'form' => $form->createView()
                    ));
                $array    = array('success' => true, 'html' => $html);
                $response = new JsonResponse($array);

                return $response;
            }
            else
            {
                $response = $this->render('ZnaikaProfileBundle:Default:registerSuccess.html.twig');

                return $response;
            }
        }

        /**
         * @return UserRegistrationRepository
         */
        private function getUserRegistrationRepository()
        {
            $userRegistrationRepository = $this->get('znaika.user_registration_repository');

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
                $html     = $this->renderView('ZnaikaProfileBundle:Default:register_form.html.twig',
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
                $response = $this->render('ZnaikaProfileBundle:Default:register.html.twig',
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
            $encoder = $this->get('znaika.register_key_encoder');
            $key     = $encoder->encode($user->getEmail() . time(), $user->getSalt());
            $passwordRecovery->setRecoveryKey($key);

            $passwordRecovery->setRecoveryTime(new \DateTime());
            $passwordRecoveryRepository = $this->get('znaika.password_recovery_repository');
            $passwordRecoveryRepository->save($passwordRecovery);

            $this->getUserMailer()->sendPasswordRecoveryConfirm($passwordRecovery);
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
            $user = $passwordRecovery->getUser();
            $this->recoverUserPassword($user);

            $html     = $this->renderView('ZnaikaProfileBundle:Default:forget_password_success_block.html.twig',
                array(
                    'form' => $form->createView()
                ));
            $array    = array('success' => true, 'html' => $html);
            $response = new JsonResponse($array);

            return $response;
        }

        /**
         * @param $form
         *
         * @internal param \Symfony\Component\HttpFoundation\Request $request
         * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
         */
        private function getPasswordRecoveryFailedResponse(Form $form)
        {
            $html     = $this->renderView('ZnaikaProfileBundle:Default:forget_password_form.html.twig',
                array(
                    'form' => $form->createView()
                ));
            $array    = array('success' => false, 'html' => $html);
            $response = new JsonResponse($array);

            return $response;
        }

        /**
         * @param User $user
         *
         * @return RedirectResponse
         */
        private function getGenerateNewPasswordSuccessResponse(User $user)
        {
            $encoder         = $this->getPasswordEncoder($user);
            $encodedPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($encodedPassword);

            $userRepository = $this->getUserRepository();
            $userRepository->save($user);

            $userAuthenticator = $this->getUserAuthenticator();
            $userAuthenticator->authenticate($user);

            $this->get('session')->getFlashBag()
                 ->add('notice', $this->get('translator')->trans('congratulations_password_changed_success'));

            return new RedirectResponse($this->generateUrl('show_user_profile', array('userId' => $user->getUserId())));
        }

        /**
         * @return PasswordRecoveryRepository
         */
        private function getPasswordRecoveryRepository()
        {
            $passwordRecoveryRepository = $this->get('znaika.password_recovery_repository');

            return $passwordRecoveryRepository;
        }

        /**
         * @param User $user
         *
         * @return PasswordEncoderInterface
         */
        private function getPasswordEncoder(User $user)
        {
            /** @var EncoderFactory $factory */
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);

            return $encoder;
        }

        /**
         * @return UserMailer
         */
        private function getUserMailer()
        {
            $userMailer = $this->get('znaika..user_mailer');

            return $userMailer;
        }

        private function setIsSocialRegisterComplete(User $user)
        {
            $isSocialRegisterComplete = $user->getVkId() || $user->getOdnoklassnikiId() || $user->getFacebookId();
            $request                  = $this->getRequest();
            $session                  = $request->getSession();
            $session->set("isSocialRegisterComplete", $isSocialRegisterComplete);
        }
    }
