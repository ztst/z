<?

    namespace Znaika\FrontendBundle\Controller;

    use FOS\MessageBundle\FormFactory\NewThreadMessageFormFactory;
    use FOS\MessageBundle\FormModel\NewThreadMessage;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\SecurityContext;
    use Znaika\FrontendBundle\Entity\Communication\Thread;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Provider\MessagesProvider;
    use Znaika\FrontendBundle\Repository\Communication\MessageRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class MessageController extends Controller
    {
        public function deleteMessageAction(Request $request)
        {
            $messageId = $request->get("messageId");

            /** @var MessageRepository $messageRepository */
            $messageRepository = $this->get('znaika_frontend.message_repository');

            $success = $messageRepository->markIsDeletedByParticipant($this->getUser(), $messageId);

            return new JsonResponse(array('success' => $success));
        }

        public function showThreadsAction()
        {
            $provider = $this->getProvider();
            $threads  = $provider->getParticipantAllThreads();

            return $this->container->get('templating')
                                   ->renderResponse('ZnaikaFrontendBundle:Message:showThreads.html.twig', array(
                    'threads' => $threads,
                ));
        }

        public function showThreadAction($threadId)
        {
            /** @var Thread $thread */
            $thread      = $this->getProvider()->getThread($threadId);
            $form        = $this->container->get('fos_message.reply_form.factory')->create($thread);
            $formHandler = $this->container->get('fos_message.reply_form.handler');

            if ($message = $formHandler->process($form))
            {
                return new RedirectResponse($this->generateUrl('show_thread', array(
                    'threadId' => $threadId
                )));
            }

            $participant = $thread->getOtherParticipant($this->getUser());

            return $this->container->get('templating')
                                   ->renderResponse('ZnaikaFrontendBundle:Message:showThread.html.twig', array(
                    'form'        => $form->createView(),
                    'thread'      => $thread,
                    'participant' => $participant
                ));
        }

        public function newMessageAction()
        {
            $form        = $this->container->get('fos_message.new_thread_form.factory')->create();
            $formHandler = $this->container->get('fos_message.new_thread_form.handler');

            if ($message = $formHandler->process($form))
            {
                return new RedirectResponse($this->generateUrl('show_thread', array(
                    'threadId' => $message->getThread()->getId()
                )));
            }

            /** @var NewThreadMessage $data */
            $data = $form->getData();

            return $this->render('ZnaikaFrontendBundle:Message:sendMessage.html.twig', array(
                'form'        => $form->createView(),
                'data'        => $data,
                'participant' => $data->getRecipient()
            ));
        }

        public function sendMessageAction(Request $request)
        {
            $userRepository = $this->get('znaika_frontend.user_repository');
            $participant    = $userRepository->getOneByUserId($request->get('to'));

            $provider = $this->getProvider();
            $thread   = $provider->getThreadWithUser($participant);
            if ($thread instanceof Thread)
            {
                return new RedirectResponse($this->generateUrl('show_thread', array(
                    'threadId' => $thread->getId()
                )));
            }

            /** @var UserRepository $userRepository */
            $form = $this->getFormForNewThreadAction($participant);

            return $this->render('ZnaikaFrontendBundle:Message:sendMessage.html.twig', array(
                'form'        => $form->createView(),
                'newThread'   => $form->getData(),
                'participant' => $participant
            ));
        }

        /**
         * @return NewThreadMessageFormFactory
         */
        private function getNewThreadFormFactory()
        {
            return $this->container->get('fos_message.new_thread_form.factory');
        }

        private function getFormForNewThreadAction($user)
        {
            $form           = $this->getNewThreadFormFactory()->create();
            $preSetFormData = $this->getPreSetFormData($user, $form);
            $form->setData($preSetFormData);

            return $form;
        }

        private function getPreSetFormData(User $user, $form)
        {
            $newThreadMessage = $form->getData();
            $newThreadMessage->setRecipient($user);

            return $newThreadMessage;
        }

        /**
         * Gets the provider service
         *
         * @return MessagesProvider
         */
        protected function getProvider()
        {
            return $this->container->get('fos_message.provider');
        }
    }

