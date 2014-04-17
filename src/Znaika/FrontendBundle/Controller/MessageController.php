<?

    namespace Znaika\FrontendBundle\Controller;

    use FOS\MessageBundle\FormFactory\NewThreadMessageFormFactory;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Entity\Communication\Message;
    use Znaika\FrontendBundle\Entity\Communication\Thread;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Provider\MessagesProvider;
    use Znaika\FrontendBundle\Repository\Communication\MessageRepository;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class MessageController extends ZnaikaController
    {
        const MESSAGES_LIMIT_ON_PAGE = 10;

        public function deleteMessageAction(Request $request)
        {
            $messageId         = $request->get("messageId");
            $messageRepository = $this->getMessageRepository();

            $success = $messageRepository->markIsDeletedByParticipant($this->getUser(), $messageId);

            return new JsonResponse(array('success' => $success));
        }

        public function showThreadsAction(Request $request)
        {
            $provider  = $this->getProvider();
            $threads   = $provider->getParticipantAllThreads();
            $recipient = $request->get("sel", false);

            return $this->render('ZnaikaFrontendBundle:Message:showThreads.html.twig', array(
                'threads'     => $threads,
                'recipientId' => $recipient,
            ));
        }

        public function getThreadAjaxAction($userId)
        {
            $userRepository = $this->getUserRepository();
            $recipient      = $userRepository->getOneByUserId($userId);
            $thread         = $this->getThreadByUser($recipient);
            $form           = $this->prepareThreadForm($recipient, $thread);

            $messages      = array();
            $messagesCount = 0;
            if ($thread instanceof Thread)
            {
                $messagesCount     = count($thread->getMessages());
                $messageRepository = $this->getMessageRepository();
                $messages          = $messageRepository->getThreadMessages($thread, 0, self::MESSAGES_LIMIT_ON_PAGE);
                $messages          = array_reverse($messages);
            }

            $html = $this->renderView('ZnaikaFrontendBundle:Message:getThreadAjax.html.twig', array(
                'form'          => $form->createView(),
                'thread'        => $thread,
                'messages'      => $messages,
                'messagesCount' => $messagesCount,
                'participant'   => $recipient,
            ));

            $result = array(
                'html'    => $html,
                'success' => true
            );

            return new JsonResponse($result);
        }

        public function showThreadPrevMessagesAction($threadId)
        {
            /** @var Thread $thread */
            $thread = $this->getProvider()->getThread($threadId);

            $messagesCount     = count($thread->getMessages());
            $messageRepository = $this->getMessageRepository();
            $messages          = $messageRepository->getThreadMessages($thread, self::MESSAGES_LIMIT_ON_PAGE,
                $messagesCount - self::MESSAGES_LIMIT_ON_PAGE);
            $messages          = array_reverse($messages);

            $html = $this->renderView('ZnaikaFrontendBundle:Message:showThreadPrevMessages.html.twig', array(
                'messages' => $messages,
            ));

            $result = array(
                'html'    => $html,
                'success' => true
            );

            return new JsonResponse($result);
        }

        public function sendMessageAjaxAction(Request $request)
        {
            $participantId  = $request->get('participantId');
            $userRepository = $this->getUserRepository();
            $recipient      = $userRepository->getOneByUserId($participantId);
            $thread         = $this->getThreadByUser($recipient);
            $form           = $this->prepareThreadForm($recipient, $thread);
            $formHandler    = $this->prepareFormHandleByThread($thread);

            $result = array(
                "success" => false
            );
            /** @var Message $message */
            $message = $formHandler->process($form);
            if ($message)
            {
                $result['success'] = true;

                $result['html']          = $this->renderView('ZnaikaFrontendBundle:Message:show_message_block.html.twig',
                    array(
                        'message' => $message,
                    ));
                $result['threadHtml']    = $this->renderView('ZnaikaFrontendBundle:Message:show_thread_last_message.html.twig',
                    array(
                        'thread' => $message->getThread(),
                    ));
                $result['participantId'] = $participantId;
            }

            return new JsonResponse($result);
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

        /**
         * @return MessageRepository
         */
        private function getMessageRepository()
        {
            /** @var MessageRepository $messageRepository */
            $messageRepository = $this->get('znaika.message_repository');

            return $messageRepository;
        }

        /**
         * @param $participant
         *
         * @return \FOS\MessageBundle\Model\ThreadInterface
         * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
         */
        private function getThreadByUser($participant)
        {
            if (!$participant instanceof User)
            {
                throw $this->createNotFoundException("Send message error: bad participantId({$participant})");
            }

            $provider = $this->getProvider();
            $thread   = $provider->getThreadWithUser($participant);

            return $thread;
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
         * @param \Znaika\ProfileBundle\Entity\User $participant
         * @param $thread
         *
         * @return mixed
         */
        private function prepareThreadForm(User $participant, $thread)
        {
            if ($thread instanceof Thread)
            {
                $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
            }
            else
            {
                $form = $this->getFormForNewThreadAction($participant);
            }

            return $form;
        }

        private function prepareFormHandleByThread($thread)
        {
            if ($thread instanceof Thread)
            {
                $formHandler = $this->container->get('fos_message.reply_form.handler');
            }
            else
            {
                $formHandler = $this->container->get('fos_message.new_thread_form.handler');
            }

            return $formHandler;
        }
    }

