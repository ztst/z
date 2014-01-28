<?
    namespace Znaika\FrontendBundle\Helper\Security;

    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\Routing\RouterInterface;
    use Symfony\Component\HttpFoundation\Session\Session;
    use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
    use Symfony\Component\Security\Core\Exception\AuthenticationException;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\SecurityContextInterface;
    use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
    use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

    class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
    {
        /**
         * @var \Symfony\Component\Routing\RouterInterface
         */
        private $router;

        /**
         * @var \Symfony\Component\HttpFoundation\Session\Session
         */
        private $session;

        /**
         * @param RouterInterface $router
         * @param Session $session
         */
        public function __construct(RouterInterface $router, Session $session)
        {
            $this->router  = $router;
            $this->session = $session;
        }

        /**
         * @param Request $request
         * @param TokenInterface $token
         *
         * @return Response
         */
        public function onAuthenticationSuccess(Request $request, TokenInterface $token)
        {
            if ($request->isXmlHttpRequest())
            {
                $array    = array('success' => true);
                $response = new JsonResponse($array);

                return $response;
            }
            else
            {
                if ($this->session->get('_security.main.target_path'))
                {
                    $url = $this->session->get('_security.main.target_path');
                }
                else
                {
                    $url = $this->router->generate('znaika_frontend_homepage');
                }

                return new RedirectResponse($url);
            }
        }

        /**
         *
         * @param Request $request
         * @param AuthenticationException $exception
         *
         * @return Response
         */
        public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
        {
            if ($request->isXmlHttpRequest())
            {
                $array    = array('success' => false, 'message' => $exception->getMessage());
                $response = new JsonResponse($array);

                return $response;
            }
            else
            {
                $request->getSession()->set(SecurityContextInterface::AUTHENTICATION_ERROR, $exception);

                return new RedirectResponse($this->router->generate('login'));
            }
        }
    }