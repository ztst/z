<?

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Component\Form\Form;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Core\Encoder\EncoderFactory;
    use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
    use Symfony\Component\Security\Core\SecurityContext;
    use Znaika\FrontendBundle\Entity\Profile\ChangeUserEmail;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Entity\Profile\PasswordRecovery;
    use Znaika\FrontendBundle\Entity\Profile\UserRegistration;
    use Znaika\FrontendBundle\Entity\Profile\Ban\Info;
    use Znaika\FrontendBundle\Form\Model\ChangePassword;
    use Znaika\FrontendBundle\Form\Model\Registration;
    use Znaika\FrontendBundle\Form\Type\ChangePasswordType;
    use Znaika\FrontendBundle\Form\Type\RegistrationType;
    use Znaika\FrontendBundle\Form\User\ChangeUserEmailType;
    use Znaika\FrontendBundle\Form\User\PasswordRecoveryType;
    use Znaika\FrontendBundle\Form\User\TeacherProfileType;
    use Znaika\FrontendBundle\Form\User\UserPhotoType;
    use Znaika\FrontendBundle\Form\User\UserProfileType;
    use Znaika\FrontendBundle\Helper\Encode\RegisterKeyEncoder;
    use Znaika\FrontendBundle\Helper\Mail\UserMailer;
    use Znaika\FrontendBundle\Helper\Odnoklassniki;
    use Znaika\FrontendBundle\Helper\Security\UserAuthenticator;
    use Znaika\FrontendBundle\Helper\Uploader\UserPhotoUploader;
    use Znaika\FrontendBundle\Helper\UserOperation\UserOperationListener;
    use Znaika\FrontendBundle\Helper\Util\FileSystem\UnixSystemUtils;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentStatus;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserBan;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserStatus;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;
    use Znaika\FrontendBundle\Helper\Util\SocialNetworkUtil;
    use Znaika\FrontendBundle\Helper\Util\UserAgentInfoProvider;
    use Znaika\FrontendBundle\Helper\Vkontakte;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoCommentRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;
    use Znaika\FrontendBundle\Repository\Profile\Badge\UserBadgeRepository;
    use Znaika\FrontendBundle\Repository\Profile\Ban\InfoRepository;
    use Znaika\FrontendBundle\Repository\Profile\ChangeUserEmailRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRegistrationRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;
    use Znaika\FrontendBundle\Repository\Profile\PasswordRecoveryRepository;
    use Znaika\FrontendBundle\Form\User\GenerateNewPasswordType;

    class UserController extends ZnaikaController
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


            return $this->render('ZnaikaFrontendBundle:User:loginAjax.html.twig',
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

        public function showUserProfileAction($userId)
        {
            $canEdit = $this->canEditProfile();
            if ($canEdit)
            {
                $currentUser = $this->getUser();
                $urlPattern  = $currentUser->getRole() == UserRole::ROLE_USER ? "edit_user_profile" : "edit_teacher_profile";

                return new RedirectResponse($this->generateUrl($urlPattern, array('userId' => $userId)));
            }

            $this->addBanReasonMessage();
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

        public function editUserProfileAction()
        {
            if (!$this->canEditProfile())
            {
                throw $this->createNotFoundException("Can't manage user");
            }
            $isSocialRegisterComplete = $this->getIsSocialRegisterComplete();
            $user                     = $this->getUser();

            $profileForm                          = $this->handleProfileForm($user);
            $userPhotoForm                        = $this->createForm(new UserPhotoType(), $user);
            $changeUserEmail                      = $this->createForm(new ChangeUserEmailType(), new ChangeUserEmail());
            $changePasswordOnRegisterCompleteForm = $this->createForm(new ChangePasswordType(), new ChangePassword());
            $changePasswordForm                   = $this->createForm(new ChangePasswordType(), new ChangePassword());

            $this->addBanReasonMessage();

            return $this->render('ZnaikaFrontendBundle:User:editUserProfile.html.twig', array(
                'profileForm'                          => $profileForm->createView(),
                'userPhotoForm'                        => $userPhotoForm->createView(),
                'changePasswordForm'                   => $changePasswordForm->createView(),
                'changePasswordOnRegisterCompleteForm' => $changePasswordOnRegisterCompleteForm->createView(),
                'changeUserEmailFrom'                  => $changeUserEmail->createView(),
                'user'                                 => $user,
                'userId'                               => $user->getUserId(),
                'isSocialRegisterComplete'             => $isSocialRegisterComplete,
            ));
        }

        public function editTeacherProfileAction()
        {
            if (!$this->canEditProfile())
            {
                throw $this->createNotFoundException("Can't manage user");
            }
            $user = $this->getUser();

            $profileForm        = $this->handleTeacherProfileForm($user);
            $userPhotoForm      = $this->createForm(new UserPhotoType(), $user);
            $changeUserEmail    = $this->createForm(new ChangeUserEmailType(), new ChangeUserEmail());
            $changePasswordForm = $this->createForm(new ChangePasswordType(), new ChangePassword());

            return $this->render('ZnaikaFrontendBundle:User:editTeacherProfile.html.twig', array(
                'profileForm'         => $profileForm->createView(),
                'userPhotoForm'       => $userPhotoForm->createView(),
                'changePasswordForm'  => $changePasswordForm->createView(),
                'changeUserEmailFrom' => $changeUserEmail->createView(),
                'user'                => $user,
                'userId'              => $user->getUserId(),
            ));
        }

        public function teacherQuestionsAction()
        {
            if (!$this->canEditProfile())
            {
                throw $this->createNotFoundException("Can't manage user");
            }
            $user = $this->getUser();

            $videoCommentRepository = $this->getVideoCommentRepository();
            $countQuestions         = $videoCommentRepository->countTeacherNotAnsweredQuestionComments($user);

            $videoRepository = $this->getVideoRepository();
            $videos          = $videoRepository->getSupervisorVideosWithQuestions($user);

            return $this->render('ZnaikaFrontendBundle:User:teacherQuestionsPage.html.twig', array(
                'user'           => $user,
                'countQuestions' => $countQuestions,
                'videos'         => $videos,
            ));
        }

        public function notVerifiedCommentsAction()
        {
            $user = $this->getUser();

            $videoCommentRepository = $this->getVideoCommentRepository();
            $countComments          = $videoCommentRepository->countModeratorNotVerifiedComments();

            $videoRepository = $this->getVideoRepository();
            $videos          = $videoRepository->getVideosWithNotVerifiedComments();

            return $this->render('ZnaikaFrontendBundle:User:notVerifiedCommentsPage.html.twig', array(
                'user'          => $user,
                'videos'        => $videos,
                'countComments' => $countComments,
            ));
        }

        public function notVerifiedPupilsAction()
        {
            $user = $this->getUser();

            $userRepository = $this->getUserRepository();
            $pupilRoles     = array(UserRole::ROLE_USER);
            $countPupils    = $userRepository->countNotVerifiedUsers($pupilRoles);
            $users          = $userRepository->getNotVerifiedUsers($pupilRoles);


            $editedUserId = $this->getRequest()->get("userId");
            $editedUser   = $editedUserId ? $userRepository->getOneByUserId($editedUserId) : null;

            return $this->render('ZnaikaFrontendBundle:User:notVerifiedPupilsPage.html.twig', array(
                'countPupils' => $countPupils,
                'users'       => $users,
                'user'        => $user,
                'editedUser'  => $editedUser,
            ));
        }

        public function approveCommentsAction()
        {
            return $this->updateCommentsStatus(VideoCommentStatus::APPROVED);
        }

        public function deleteCommentsAction()
        {
            return $this->updateCommentsStatus(VideoCommentStatus::DELETED);
        }

        public function approveUsersAction()
        {
            return $this->updateUsersStatus(UserStatus::ACTIVE);
        }

        public function deleteUsersAction()
        {
            return $this->updateUsersStatus(UserStatus::BANNED);
        }

        public function getUserProfileAjaxAction($userId)
        {
            $user = $this->getUserRepository()->getOneByUserId($userId);

            $html = $this->renderView("ZnaikaFrontendBundle:User:getUserProfileAjax.html.twig", array(
                'user' => $user
            ));

            $result = array(
                'html'    => $html,
                'success' => true
            );

            return new JsonResponse($result);
        }

        public function changeEmailAction(Request $request)
        {
            if (!$this->canEditProfile())
            {
                throw $this->createNotFoundException("Can't manage user");
            }
            $user        = $this->getUser();
            $changeEmail = $this->createChangeUserEmail($user);

            return $this->handleChangeEmailForm($request, $changeEmail);
        }

        public function completeChangeEmailAction($key)
        {
            $changeEmailRepository = $this->getChangeUserEmailRepository();
            $changeEmail           = $changeEmailRepository->getOneByChangeKey($key);
            if (!$changeEmail)
            {
                return $this->createNotFoundException("change email not found");
            }

            $user        = $changeEmail->getUser();
            $expiredTime = $changeEmail->getCreatedTime()->add(new \DateInterval(ChangeUserEmail::EXPIRED_TIME));
            $currentTime = new \DateTime("now");
            if ($currentTime > $expiredTime)
            {
                return $this->recreateChangeUserEmail($user);
            }

            $user->setEmail($changeEmail->getNewEmail());
            $this->getUserRepository()->save($user);

            $changeEmailRepository->delete($changeEmail);

            $userAuthenticator = $this->getUserAuthenticator();
            $userAuthenticator->authenticate($user);

            $this->addCongratulationFlashBoxMessage();

            return new RedirectResponse($this->generateUrl('show_user_profile', array('userId' => $user->getUserId())));
        }

        public function changePasswordAction(Request $request)
        {
            if (!$this->canEditProfile())
            {
                throw $this->createNotFoundException("Can't manage user");
            }
            $currentUser = $this->getUser();

            $changePasswordModel = new ChangePassword();
            $form                = $this->createForm(new ChangePasswordType(), $changePasswordModel);

            $form->handleRequest($request);

            $success = false;
            if ($form->isSubmitted() && $form->isValid())
            {
                $encoder = $this->getPasswordEncoder($currentUser);
                $currentUser->setPassword($encoder->encodePassword($changePasswordModel->getNewPassword(),
                    $currentUser->getSalt()));
                $userRepository = $this->getUserRepository();
                $userRepository->save($currentUser);

                $success = true;
            }

            return JsonResponse::create(array(
                "success" => $success
            ));
        }

        public function editUserPhotoAction(Request $request)
        {
            if (!$this->canEditProfile())
            {
                throw $this->createNotFoundException("Can't manage user");
            }
            /** @var User $user */
            $user           = $this->getUser();
            $userRepository = $this->getUserRepository();

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

            $data = array(
                "bigPhotoUrl"   => $user->getBigPhotoUrl(),
                "smallPhotoUrl" => $user->getSmallPhotoUrl(),
                "success"       => $success
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
            $user           = $currentUser;
            $user->setPhotoFileName(null);
            $userRepository->save($user);

            $userPhotoUploader = $this->getUserPhotoUploader();
            $userPhotoUploader->deletePhoto($user);

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
            $encoder = $this->get('znaika_frontend.register_key_encoder');
            $key     = $encoder->encode($user->getEmail(), $user->getSalt());
            $userRegistration->setRegisterKey($key);
            $userRegistration->setCreatedTime(new \DateTime());

            $userRegistrationRepository = $this->getUserRegistrationRepository();
            $userRegistrationRepository->save($userRegistration);

            return $userRegistration;
        }

        /**
         * @param User $user
         *
         * @return ChangeUserEmail
         */
        private function createChangeUserEmail(User $user)
        {
            $changeUserEmail = $user->getLastChangeUserEmail();

            /** @var RegisterKeyEncoder $encoder */
            $encoder = $this->get('znaika_frontend.register_key_encoder');
            $key     = $encoder->encode($user->getEmail(), time());
            $changeUserEmail->setChangeKey($key);
            $changeUserEmail->setCreatedTime(new \DateTime());

            return $changeUserEmail;
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
            $passwordRecoveryRepository = $this->get('znaika_frontend.password_recovery_repository');

            return $passwordRecoveryRepository;
        }

        /**
         * @param $user
         *
         * @return \Symfony\Component\Form\Form
         */
        private function handleProfileForm(User $user)
        {
            $request = $this->getRequest();
            $form    = $this->createForm(new UserProfileType(), $user);

            if ($request->request->has('user_profile_type'))
            {
                $form->handleRequest($request);
                if ($form->isValid())
                {
                    $listener = $this->getUserOperationListener();
                    $listener->onEditProfile($user);

                    $user->setStatus(UserStatus::NOT_VERIFIED);
                    $userRepository = $this->getUserRepository();
                    $userRepository->save($user);
                }
            }

            return $form;
        }

        /**
         * @param $user
         *
         * @return \Symfony\Component\Form\Form
         */
        private function handleTeacherProfileForm(User $user)
        {
            $request = $this->getRequest();
            $form    = $this->createForm(new TeacherProfileType(), $user);

            if ($request->request->has('teacher_profile_type'))
            {
                $form->handleRequest($request);
                if ($form->isValid())
                {
                    $userRepository = $this->getUserRepository();
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
         * @return boolean
         */
        private function canEditProfile()
        {
            $request     = $this->getRequest();
            $userId      = $request->get('userId');
            $currentUser = $this->getUser();

            $canEdit = false;
            if ($currentUser instanceof User)
            {
                $ownProfile = $currentUser->getUserId() == $userId;
                $canEdit    = $ownProfile && !UserBan::isPermanentlyBanned($currentUser);
            }

            return $canEdit;
        }

        private function addBanReasonMessage()
        {
            $user = $this->getUser();

            if ($user instanceof User && UserBan::isBanned($user))
            {
                $templateName = null;
                $reason       = $user->getBanReason();
                switch ($reason)
                {
                    case UserBan::PERMANENTLY:
                        $templateName = "permanently_ban_message";
                        break;
                    case UserBan::PROFILE:
                        $templateName = "ban_profile_message";
                        break;
                    case UserBan::COMMENT:
                        $templateName = "ban_comment_message";
                        break;
                }

                if ($templateName)
                {
                    $templateName = "ZnaikaFrontendBundle:User:Ban\\{$templateName}.html.twig";
                    $message      = $this->renderView($templateName, array(
                        'user' => $user
                    ));

                    $this->get('session')->getFlashBag()
                         ->add('notice', $this->get('translator')->trans($message));
                }
            }
        }

        /**
         * @return ChangeUserEmailRepository
         */
        private function getChangeUserEmailRepository()
        {
            $changeUserEmailRepository = $this->get("znaika_frontend.change_user_email_repository");

            return $changeUserEmailRepository;
        }

        /**
         * @return UserMailer
         */
        private function getUserMailer()
        {
            $userMailer = $this->get('znaika_frontend.user_mailer');

            return $userMailer;
        }

        private function setIsSocialRegisterComplete(User $user)
        {
            $isSocialRegisterComplete = $user->getVkId() || $user->getOdnoklassnikiId() || $user->getFacebookId();
            $request                  = $this->getRequest();
            $session                  = $request->getSession();
            $session->set("isSocialRegisterComplete", $isSocialRegisterComplete);
        }

        private function getIsSocialRegisterComplete()
        {
            $request = $this->getRequest();
            $session = $request->getSession();

            return $session->get("isSocialRegisterComplete", false);
        }

        /**
         * @return VideoCommentRepository
         */
        private function getVideoCommentRepository()
        {
            return $this->get('znaika_frontend.video_comment_repository');
        }

        /**
         * @return VideoRepository
         */
        private function getVideoRepository()
        {
            return $this->get('znaika_frontend.video_repository');
        }

        /**
         * @param $status
         *
         * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
         * @return JsonResponse
         */
        private function updateCommentsStatus($status)
        {
            $request                = $this->getRequest();
            $videoCommentRepository = $this->getVideoCommentRepository();
            $comments               = $videoCommentRepository->getByVideoCommentIds($request->get("ids"));

            $videoId = 0;
            $success = false;
            $ids     = array();

            foreach ($comments as $comment)
            {
                if ($comment->getStatus() != $status)
                {
                    $comment->setStatus($status);
                    $videoCommentRepository->save($comment);
                    $videoId = $videoId == 0 ? $comment->getVideo()->getVideoId() : $videoId;
                    array_push($ids, $comment->getVideoCommentId());
                    $success = true;
                }
            }

            return JsonResponse::create(array(
                "success" => $success,
                "videoId" => $videoId,
                "ids"     => $ids
            ));
        }

        /**
         * @param $status
         *
         * @return JsonResponse
         */
        private function updateUsersStatus($status)
        {
            $request        = $this->getRequest();
            $reason         = $request->get("reason", UserBan::PROFILE);
            $userRepository = $this->getUserRepository();
            $users          = $userRepository->getByUserIds($request->get("ids"));

            $success = false;
            $ids     = array();

            foreach ($users as $user)
            {
                if ($user->getStatus() != $status)
                {
                    $this->setUserStatus($user, $status, $reason);
                    $user->setUpdatedTime(new \DateTime());
                    $userRepository->save($user);
                    array_push($ids, $user->getUserId());
                    $success = true;
                }
            }

            return JsonResponse::create(array(
                "success" => $success,
                "ids"     => $ids
            ));
        }

        /**
         * @param \Znaika\FrontendBundle\Entity\Profile\User $user
         * @param $status
         * @param $reason
         */
        private function setUserStatus(User $user, $status, $reason)
        {
            $user->setStatus($status);
            if ($status == UserStatus::BANNED)
            {
                if ($user->getBanReason() != UserBan::NO_REASON)
                {
                    $reason = UserBan::PERMANENTLY;
                }

                $user->setBanReason($reason);
                $this->saveBanInfo($user, $reason);

                $userMailer = $this->getUserMailer();
                $userMailer->sendUserBanMessage($user);
            }
        }

        /**
         * @param User $user
         * @param $reason
         */
        private function saveBanInfo(User $user, $reason)
        {
            $banInfo = new Info();
            $banInfo->setCreatedTime(new \DateTime());
            $banInfo->setUser($user);
            $banInfo->setReason($reason);

            /** @var InfoRepository $repository */
            $repository = $this->get("znaika_frontend.ban_info_repository");
            $repository->save($banInfo);
        }

        private function addCongratulationFlashBoxMessage()
        {
            $this->get('session')->getFlashBag()
                 ->add('notice', $this->get('translator')->trans('congratulations_change_email_success'));
        }

        /**
         * @param $user
         *
         * @return Response
         */
        private function recreateChangeUserEmail($user)
        {
            $changeEmail           = $this->createChangeUserEmail($user);
            $changeEmailRepository = $this->getChangeUserEmailRepository();
            $changeEmailRepository->save($changeEmail);
            $this->getUserMailer()->sendChangeEmailConfirm($changeEmail);

            return $this->render('ZnaikaFrontendBundle:User:expiredUserChangeEmailKey.html.twig');
        }

        /**
         * @param Request $request
         * @param ChangeUserEmail $changeEmail
         *
         * @return Response|static
         */
        private function handleChangeEmailForm(Request $request, ChangeUserEmail $changeEmail)
        {
            $form = $this->createForm(new ChangeUserEmailType(), $changeEmail);
            $form->handleRequest($request);

            $success   = false;
            $emailBusy = false;
            if ($form->isSubmitted() && $form->isValid())
            {
                $userWithEmail = $this->getUserRepository()->getOneByEmail($changeEmail->getNewEmail());
                if ($userWithEmail && $userWithEmail->isEnabled())
                {
                    $emailBusy = true;
                }
                else
                {
                    $changeEmailRepository = $this->getChangeUserEmailRepository();

                    $changeEmailRepository->save($changeEmail);
                    $this->getUserMailer()->sendChangeEmailConfirm($changeEmail);
                    $success = true;
                }
            }

            return JsonResponse::create(array(
                "success"   => $success,
                "emailBusy" => $emailBusy
            ));
        }
    }
