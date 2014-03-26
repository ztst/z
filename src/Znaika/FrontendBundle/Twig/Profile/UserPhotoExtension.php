<?php

    namespace Znaika\FrontendBundle\Twig\Profile;

    use Znaika\FrontendBundle\Entity\Profile\User;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;

    class UserPhotoExtension extends \Twig_Extension
    {
        /**
         * @var \Twig_Environment
         */
        private $twig;

        /**
         * @var ContainerInterface
         */
        private $container;


        public function __construct(\Twig_Environment $twig, ContainerInterface $container)
        {
            $this->twig      = $twig;
            $this->container = $container;
        }

        public function getFunctions()
        {
            return array(
                'user_photo_url' => new \Twig_Function_Method($this, 'getUserPhotoUrl'),
            );
        }

        public function getUserPhotoUrl(User $user)
        {
            $url = "";
            if ($user->getHasPhoto())
            {
                $url = $user->getPhotoUrl();
            }
            elseif ($user->getRole() == UserRole::ROLE_USER)
            {
                $url = $this->container->get('templating.helper.assets')->getUrl("images/user-profile/user-photo-placeholder.png");
            }
            else
            {
                $url = $this->container->get('templating.helper.assets')->getUrl("images/teacher-page/teacher-photo-placeholder.png");
            }

            return $url;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'user_photo_extension';
        }
    }
