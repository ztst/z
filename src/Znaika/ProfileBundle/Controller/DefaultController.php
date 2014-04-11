<?php

    namespace Znaika\ProfileBundle\Controller;

    use Symfony\Component\Form\Form;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
    use Symfony\Component\Security\Core\Encoder\EncoderFactory;
    use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
    use Znaika\FrontendBundle\Controller\ZnaikaController;
    use Znaika\ProfileBundle\Entity\ChangeUserEmail;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Entity\PasswordRecovery;
    use Znaika\ProfileBundle\Entity\Ban\Info;
    use Znaika\ProfileBundle\Form\Model\ChangePassword;
    use Znaika\ProfileBundle\Form\Type\ChangePasswordType;
    use Znaika\ProfileBundle\Form\ChangeUserEmailType;
    use Znaika\ProfileBundle\Form\PasswordRecoveryType;
    use Znaika\ProfileBundle\Form\TeacherProfileType;
    use Znaika\ProfileBundle\Form\UserPhotoType;
    use Znaika\ProfileBundle\Form\UserProfileType;
    use Znaika\ProfileBundle\Helper\Encode\RegisterKeyEncoder;
    use Znaika\ProfileBundle\Helper\Mail\UserMailer;
    use Znaika\ProfileBundle\Helper\Security\UserAuthenticator;
    use Znaika\ProfileBundle\Helper\Uploader\UserPhotoUploader;
    use Znaika\ProfileBundle\Helper\UserOperation\UserOperationListener;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentStatus;
    use Znaika\ProfileBundle\Helper\Util\UserBan;
    use Znaika\ProfileBundle\Helper\Util\UserStatus;
    use Znaika\ProfileBundle\Helper\Util\UserRole;
    use Znaika\FrontendBundle\Helper\Util\UserAgentInfoProvider;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoCommentRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;
    use Znaika\ProfileBundle\Repository\Badge\UserBadgeRepository;
    use Znaika\ProfileBundle\Repository\Ban\InfoRepository;
    use Znaika\ProfileBundle\Repository\ChangeUserEmailRepository;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class DefaultController extends ZnaikaController
    {
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

            return $this->render('ZnaikaProfileBundle:Default:showUserProfile.html.twig', array(
                'user' => $user,
            ));
        }

        public function editUserProfileAction(Request $request)
        {
            if (!$this->canEditProfile())
            {
                throw new AccessDeniedHttpException("Can't manage user");
            }
            $isSocialRegisterComplete = $this->getIsSocialRegisterComplete();
            $user                     = $this->getUser();

            $profileForm                          = $this->handleProfileForm($user, $request);
            $userPhotoForm                        = $this->createForm(new UserPhotoType(), $user);
            $changeUserEmail                      = $this->createForm(new ChangeUserEmailType(), new ChangeUserEmail());
            $changePasswordOnRegisterCompleteForm = $this->createForm(new ChangePasswordType(), new ChangePassword());
            $changePasswordForm                   = $this->createForm(new ChangePasswordType(), new ChangePassword());

            $this->addBanReasonMessage();

            return $this->render('ZnaikaProfileBundle:Default:editUserProfile.html.twig', array(
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
                throw new AccessDeniedHttpException("Can't manage user");
            }
            $user = $this->getUser();

            $profileForm        = $this->handleTeacherProfileForm($user);
            $userPhotoForm      = $this->createForm(new UserPhotoType(), $user);
            $changeUserEmail    = $this->createForm(new ChangeUserEmailType(), new ChangeUserEmail());
            $changePasswordForm = $this->createForm(new ChangePasswordType(), new ChangePassword());

            return $this->render('ZnaikaProfileBundle:Default:editTeacherProfile.html.twig', array(
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
                throw new AccessDeniedHttpException("Can't manage user");
            }
            $user = $this->getUser();

            $videoCommentRepository = $this->getVideoCommentRepository();
            $countQuestions         = $videoCommentRepository->countTeacherNotAnsweredQuestionComments($user);

            $videoRepository = $this->getVideoRepository();
            $videos          = $videoRepository->getSupervisorVideosWithQuestions($user);

            return $this->render('ZnaikaProfileBundle:Default:teacherQuestionsPage.html.twig', array(
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

            return $this->render('ZnaikaProfileBundle:Default:notVerifiedCommentsPage.html.twig', array(
                'user'          => $user,
                'videos'        => $videos,
                'countComments' => $countComments,
            ));
        }

        public function notVerifiedPupilsAction(Request $request)
        {
            $user = $this->getUser();

            $userRepository = $this->getUserRepository();
            $pupilRoles     = array(UserRole::ROLE_USER);
            $countPupils    = $userRepository->countNotVerifiedUsers($pupilRoles);
            $users          = $userRepository->getNotVerifiedUsers($pupilRoles);


            $editedUserId = $request->get("userId");
            $editedUser   = $editedUserId ? $userRepository->getOneByUserId($editedUserId) : null;

            return $this->render('ZnaikaProfileBundle:Default:notVerifiedPupilsPage.html.twig', array(
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

            $html = $this->renderView("ZnaikaProfileBundle:Default:getUserProfileAjax.html.twig", array(
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
                throw new AccessDeniedHttpException("Can't manage user");
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
                throw new AccessDeniedHttpException("Can't manage user");
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
                throw new AccessDeniedHttpException("Can't manage user");
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

            $data       = array(
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
                throw new AccessDeniedHttpException("Can't manage user");
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
            return $this->get('znaika.user_operation_listener');
        }

        /**
         * @return UserBadgeRepository
         */
        private function getUserBadgeRepository()
        {
            return $this->get('znaika.user_badge_repository');
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
            $encoder = $this->get('znaika.register_key_encoder');
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
         * @param \Znaika\ProfileBundle\Entity\User $user
         * @param \Symfony\Component\HttpFoundation\Request $request
         *
         * @return \Symfony\Component\Form\Form
         */
        private function handleProfileForm(User $user, Request $request)
        {
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
            $userPhotoUploader = $this->get('znaika.user_photo_uploader');

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
                    $templateName = "ZnaikaProfileBundle:Default:Ban\\{$templateName}.html.twig";
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
            $changeUserEmailRepository = $this->get("znaika.change_user_email_repository");

            return $changeUserEmailRepository;
        }

        /**
         * @return UserMailer
         */
        private function getUserMailer()
        {
            $userMailer = $this->get('znaika.user_mailer');

            return $userMailer;
        }

        private function getIsSocialRegisterComplete()
        {
            $request                  = $this->getRequest();
            $session                  = $request->getSession();
            $isSocialRegisterComplete = $session->get("isSocialRegisterComplete", false);
            $session->set("isSocialRegisterComplete", false);

            return $isSocialRegisterComplete;
        }

        /**
         * @return VideoCommentRepository
         */
        private function getVideoCommentRepository()
        {
            return $this->get('znaika.video_comment_repository');
        }

        /**
         * @return VideoRepository
         */
        private function getVideoRepository()
        {
            return $this->get('znaika.video_repository');
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
         * @param \Znaika\ProfileBundle\Entity\User $user
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
            $repository = $this->get("znaika.ban_info_repository");
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

            return $this->render('ZnaikaProfileBundle:Default:expiredUserChangeEmailKey.html.twig');
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
