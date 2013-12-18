<?

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Form\Model\Registration;
    use Znaika\FrontendBundle\Form\Type\RegistrationType;
    use Znaika\FrontendBundle\Form\User\UserProfileType;

    class UserController extends Controller
    {
        public function registerAction(Request $request)
        {
            $registration = new Registration();
            $form         = $this->createForm(new RegistrationType(), $registration);
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $userInfo = $registration->getUser();
                $factory  = $this->get('security.encoder_factory');

                $encoder  = $factory->getEncoder($userInfo);
                $password = $encoder->encodePassword($userInfo->getPassword(), $userInfo->getSalt());
                $userInfo->setPassword($password);

                $userInfoRepository = $this->get('user_info_repository');
                $userInfoRepository->save($userInfo);

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
            $canEdit     = ($currentUser && $currentUser->getUserInfoId() == $userId);

            $userInfoRepository = $this->get('user_info_repository');
            $userInfo           = $userInfoRepository->getOneByUserId($userId);

            $form = $this->createForm(new UserProfileType(), $userInfo, array( 'readonly' => !$canEdit ));

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
            $canEdit     = ($currentUser && $currentUser->getUserInfoId() == $userId);

            if ( !$canEdit )
            {
                throw $this->createNotFoundException('The product does not exist');
            }
            $userInfoRepository = $this->get('user_info_repository');
            $userInfo           = $userInfoRepository->getOneByUserId($userId);

            $form = $this->createForm(new UserProfileType(), $userInfo, array( 'readonly' => !$canEdit ));

            $form->handleRequest($request);
            if ($form->isValid())
            {
                $userInfoRepository->save($userInfo);
            }

            return new RedirectResponse($this->generateUrl('show_user_profile', array(
                'userId' => $userId
            )));
        }


    }
