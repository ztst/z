<?

    namespace Znaika\FrontendBUndle\EventListener;

    use Symfony\Bundle\WebProfilerBundle\Controller\ExceptionController;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
    use Znaika\FrontendBundle\Controller\ZnaikaController;

    class BeforeControllerListener
    {
        private $context;

        public function __construct(ContainerInterface $context)
        {
            $this->context = $context;
        }

        public function onKernelController(FilterControllerEvent $event)
        {
            $controller = $event->getController();

            if (!is_array($controller))
            {
                return;
            }

            $controllerObject = $controller[0];
            if ($controllerObject instanceof ExceptionController)
            {
                return;
            }

            if ($controllerObject instanceof ZnaikaController)
            {
                $response = $controllerObject->initialize($event->getRequest(), $this->context);
                if ($response)
                {
                    $event->setController(function () use ($response)
                    {
                        return $response;
                    });
                }
            }
        }
    }