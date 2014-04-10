<?php

    namespace Znaika\ProfileBundle\Twig;

    use Symfony\Component\Security\Core\SecurityContextInterface;
    use Znaika\ProfileBundle\Helper\Util\UserRole;

    class SecurityExtension extends \Twig_Extension
    {
        /**
         * @var SecurityContextInterface
         */
        private $context;

        public function __construct(SecurityContextInterface $context = null)
        {
            $this->context = $context;
        }

        /**
         * {@inheritdoc}
         */
        public function getFunctions()
        {
            return array(
                'is_admin'     => new \Twig_Function_Method($this, 'isAdmin'),
                'is_moderator' => new \Twig_Function_Method($this, 'isModerator'),
                'is_teacher'   => new \Twig_Function_Method($this, 'isTeacher'),
                'is_user'      => new \Twig_Function_Method($this, 'isUser'),
            );
        }

        /**
         * Returns the name of the extension.
         *
         * @return string The extension name
         */
        public function getName()
        {
            return 'znaika_security';
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
            if (null === $this->context || !$this->context->getToken())
            {
                return false;
            }

            return $this->context->isGranted($role);
        }
    }
