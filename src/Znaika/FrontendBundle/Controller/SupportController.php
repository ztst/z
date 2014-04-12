<?php

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Znaika\FrontendBundle\Entity\Communication\Support;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Form\Communication\SupportType;
    use Znaika\ProfileBundle\Helper\Mail\UserMailer;
    use Znaika\FrontendBundle\Helper\Support\SupportStatus;

    class SupportController extends ZnaikaController
    {
        public function supportCreateSuccessAction()
        {
            return $this->render('ZnaikaFrontendBundle:Support:supportCreateSuccess.html.twig');
        }

        public function addSupportFormAction(Request $request)
        {
            $support = $this->prepareSupportEntity();
            $form    = $this->createForm(new SupportType(), $support);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $support->setStatus(SupportStatus::OPEN);

                $supportRepository = $this->get('znaika.support_repository');
                $supportRepository->save($support);

                $this->sendEmailToSuppor($support);

                return new RedirectResponse($this->generateUrl('support_create_success'));
            }

            return $this->render('ZnaikaFrontendBundle:Support:addSupportForm.html.twig', array(
                'form' => $form->createView()
            ));
        }

        /**
         * @return Support
         */
        private function prepareSupportEntity()
        {
            $support = new Support();
            $user = $this->getUser();
            if ($user instanceof User)
            {
                $support->setEmail($user->getEmail());
                $support->setName((string)$user);
            }

            return $support;
        }

        /**
         * @param $support
         */
        private function sendEmailToSuppor($support)
        {
            $supportEmails = $this->container->getParameter('support_emails');
            /** @var UserMailer $userMailer */
            $userMailer = $this->get("znaika.user_mailer");
            $userMailer->sendSupportEmail($support, $supportEmails);
        }
    }
