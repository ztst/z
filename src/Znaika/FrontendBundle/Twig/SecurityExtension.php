<?

    namespace Znaika\FrontendBundle\Twig;

    use Symfony\Component\Security\Core\SecurityContextInterface;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;

    /**
     * SecurityExtension exposes security context features.
     *
     * @author Fabien Potencier <fabien@symfony.com>
     */
    class SecurityExtension extends \Twig_Extension
    {
        private $context;

        public function __construct(SecurityContextInterface $context = null)
        {
            $this->context = $context;
        }

        public function isAdmin()
        {
            return $this->isGranted(UserRole::getSecurityTextByRole(UserRole::ROLE_ADMIN));
        }

        public function isTeacher()
        {
            return $this->isGranted(UserRole::getSecurityTextByRole(UserRole::ROLE_TEACHER));
        }

        public function isUser()
        {
            return $this->isGranted(UserRole::getSecurityTextByRole(UserRole::ROLE_USER));
        }

        public function isModerator()
        {
            return $this->isGranted(UserRole::getSecurityTextByRole(UserRole::ROLE_MODERATOR));
        }

        private function isGranted($role)
        {
            if (null === $this->context)
            {
                return false;
            }

            return $this->context->isGranted($role);
        }

        /**
         * {@inheritdoc}
         */
        public function getFunctions()
        {
            return array(
                new \Twig_SimpleFunction('is_admin', array($this, 'isAdmin')),
                new \Twig_SimpleFunction('is_moderator', array($this, 'isModerator')),
                new \Twig_SimpleFunction('is_teacher', array($this, 'isTeacher')),
                new \Twig_SimpleFunction('is_user', array($this, 'isUser')),
            );
        }

        /**
         * Returns the name of the extension.
         *
         * @return string The extension name
         */
        public function getName()
        {
            return 'security';
        }
    }
