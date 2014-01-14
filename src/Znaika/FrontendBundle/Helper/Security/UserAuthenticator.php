<?
    namespace Znaika\FrontendBundle\Helper\Security;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class UserAuthenticator
    {
        const PROVIDER_KEY = "secured_area";
        protected $container;

        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        public function authenticate(User $user)
        {
            $securityContext = $this->container->get('security.context');
            $token = new UsernamePasswordToken($user, null, UserAuthenticator::PROVIDER_KEY, $user->getRoles());
            $securityContext->setToken($token);
        }
    }