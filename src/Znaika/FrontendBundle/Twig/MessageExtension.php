<?php

    namespace Znaika\FrontendBundle\Twig;

    use Znaika\FrontendBundle\Entity\Communication\Thread;
    use Znaika\FrontendBundle\Repository\Communication\MessageRepository;
    use Znaika\ProfileBundle\Entity\User;

    class MessageExtension extends \Twig_Extension
    {
        /**
         * @var MessageRepository
         */
        private $messageRepository;

        public function __construct(MessageRepository $messageRepository)
        {
            $this->messageRepository = $messageRepository;
        }

        public function getFunctions()
        {
            return array(
                'count_unread_messages' => new \Twig_Function_Method($this, 'countUnreadMessages'),
                'last_thread_message'   => new \Twig_Function_Method($this, 'getLastThreadMessage'),
            );
        }

        public function getLastThreadMessage(Thread $thread)
        {
            return $this->messageRepository->getLastThreadMessage($thread);
        }

        public function countUnreadMessages(User $user, Thread $thread)
        {
            $count = $this->messageRepository->countUnreadThreadMessageByParticipant($user, $thread);
            return $count ? "+{$count}" : "";
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_message_extension';
        }
    }
