<?php

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Znaika\FrontendBundle\Entity\Communication\Support;
    use Znaika\FrontendBundle\Form\Communication\SupportType;
    use Znaika\FrontendBundle\Helper\Support\SupportStatus;

    class SupportController extends Controller
    {
        public function addSupportFormAction(Request $request)
        {
            $support = new Support();
            $form    = $this->createForm(new SupportType(), $support);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $support->setStatus(SupportStatus::OPEN);

                $supportRepository = $this->get('znaika_frontend.support_repository');
                $supportRepository->save($support);

                return new RedirectResponse($this->generateUrl('support'));
            }

            return $this->render('ZnaikaFrontendBundle:Support:addSupportForm.html.twig', array(
                'form' => $form->createView()
            ));
        }
    }
