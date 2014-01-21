<?

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\SecurityContext;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Form\Model\Registration;
    use Znaika\FrontendBundle\Form\Type\RegistrationType;
    use Znaika\FrontendBundle\Form\User\UserProfileType;
    use Znaika\FrontendBundle\Helper\Encode\RegisterKeyEncoder;
    use Znaika\FrontendBundle\Helper\Security\UserAuthenticator;
    use Znaika\FrontendBundle\Helper\UserOperation\UserOperationHandler;
    use Znaika\FrontendBundle\Helper\UserOperation\UserOperationListener;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserStatus;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class UserController extends Controller
    {
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

            $referer = $request->headers->get('referer');

            return $this->render(
                        'ZnaikaFrontendBundle:User:login.html.twig',
                            array(
                                // last username entered by the user
                                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                                'error'         => $error,
                                'referer'       => $referer,
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

            /** @var UserAuthenticator $userAuthenticator */
            $userAuthenticator = $this->get('znaika_frontend.user_authenticator');
            $userAuthenticator->authenticate($user);

            $listener = $this->getUserOperationListener();
            $listener->onRegistration($user);

            return new RedirectResponse($this->generateUrl('show_user_profile', array('userId' => $user->getUserId())));
        }

        public function registerAction(Request $request)
        {
            $registration = new Registration();
            /** @var UserRepository $userRepository */
            $userRepository = $this->get('znaika_frontend.user_repository');
            $form           = $this->createForm(new RegistrationType($userRepository), $registration);
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $user = $registration->getUser();

                $this->registerUser($user);

                return $this->render('ZnaikaFrontendBundle:User:registerSuccess.html.twig');
            }

            return $this->render('ZnaikaFrontendBundle:User:register.html.twig',
                array(
                    'form' => $form->createView()
                )
            );
        }

        public function showUserProfileAction($userId)
        {
            $currentUser = $this->getUser();
            $ownProfile  = ($currentUser && $currentUser->getUserId() == $userId);

            /** @var UserRepository $userRepository */
            $userRepository = $this->get('znaika_frontend.user_repository');
            $user           = $userRepository->getOneByUserId($userId);

            $form = $this->createForm(new UserProfileType($userRepository), $user, array('readonly' => !$ownProfile));

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

            /** @var UserRepository $userRepository */
            $userRepository = $this->get('znaika_frontend.user_repository');
            $user           = $userRepository->getOneByUserId($userId);

            $form = $this->createForm(new UserProfileType($userRepository), $user, array('readonly' => !$canEdit));

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
        protected function getUserOperationListener()
        {
            return $this->get('znaika_frontend.user_operation_listener');
        }

        private function registerUser(User $user)
        {
            $user->setCreatedTime(new \DateTime());
            $user->setStatus(UserStatus::REGISTERED);

            $factory  = $this->get('security.encoder_factory');
            $encoder  = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);

            $userRepository = $this->get('znaika_frontend.user_repository');
            $userRepository->save($user);

            $userRegistration = $this->createUserRegistration($user);

            $this->get('znaika_frontend.user_mailer')->sendRegisterConfirm($userRegistration);
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
    }
