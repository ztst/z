<?

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\SecurityContext;
    use Znaika\FrontendBundle\Form\Model\Registration;
    use Znaika\FrontendBundle\Form\Type\RegistrationType;
    use Znaika\FrontendBundle\Form\User\UserProfileType;

    class UserController extends Controller
    {
        public function loginAction(Request $request)
        {
            $session = $request->getSession();

            // get the login error if there is one
            if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
            {
                $error = $request->attributes->get( SecurityContext::AUTHENTICATION_ERROR );
            } else
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

        public function registerAction(Request $request)
        {
            $registration = new Registration();
            $form         = $this->createForm(new RegistrationType(), $registration);
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $user = $registration->getUser();
                $factory  = $this->get('security.encoder_factory');

                $encoder  = $factory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);

                $userRepository = $this->get('user_info_repository');
                $userRepository->save($user);

                return new RedirectResponse($this->generateUrl('znaika_frontend_homepage'));
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
            $canEdit     = ($currentUser && $currentUser->getUserId() == $userId);

            $userRepository = $this->get('user_info_repository');
            $user           = $userRepository->getOneByUserId($userId);

            $form = $this->createForm(new UserProfileType(), $user, array( 'readonly' => !$canEdit ));

            return $this->render('ZnaikaFrontendBundle:User:showUserProfile.html.twig', array(
                'form'    => $form->createView(),
                'canEdit' => $canEdit,
                'userId'  => $userId,
            ));
        }


        public function editUserProfileAction(Request $request)
        {
            $userId      = $request->get('userId');
            $currentUser = $this->getUser();
            $canEdit     = ($currentUser && $currentUser->getUserId() == $userId);

            if ( !$canEdit )
            {
                throw $this->createNotFoundException("Can't manage user");
            }
            $userRepository = $this->get('user_info_repository');
            $user           = $userRepository->getOneByUserId($userId);

            $form = $this->createForm(new UserProfileType(), $user, array( 'readonly' => !$canEdit ));

            $form->handleRequest($request);
            if ($form->isValid())
            {
                $userRepository->save($user);
            }

            return new RedirectResponse($this->generateUrl('show_user_profile', array(
                'userId' => $userId
            )));
        }


    }
