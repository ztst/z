<?
    namespace Znaika\FrontendBUndle\EventListener;

    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use FOS\MessageBundle\Event\MessageEvent;
    use FOS\MessageBundle\Event\FOSMessageEvents as Event;
    use FOS\MessageBundle\ModelManager\MessageManagerInterface;

    class MessageSendSubscriber implements EventSubscriberInterface
    {
        /**
         * @var MessageManagerInterface
         */
        private $messageManager;

        public function __construct(MessageManagerInterface $messageManager)
        {
            $this->messageManager = $messageManager;
        }

        public static function getSubscribedEvents()
        {
            return array(
                Event::POST_SEND => 'markAsReadBySender'
            );
        }

        public function markAsReadBySender(MessageEvent $event)
        {
            $message = $event->getMessage();
            $sender  = $message->getSender();

            $this->messageManager->markAsReadByParticipant($message, $sender);
            $this->messageManager->saveMessage($message);
        }
    }