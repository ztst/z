<?
    namespace Znaika\ProfileBundle\Helper\Security;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
    use Znaika\ProfileBundle\Entity\User;

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