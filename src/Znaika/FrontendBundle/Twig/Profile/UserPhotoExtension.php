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
                'user_photo_big_url' => new \Twig_Function_Method($this, 'getUserPhotoBigUrl'),
                'user_photo_small_url' => new \Twig_Function_Method($this, 'getUserPhotoSmallUrl')
            );
        }

        public function getUserPhotoBigUrl(User $user)
        {
            $url = "";
            if ($user->getHasPhoto())
            {
                $url = $user->getPhotoUrl();
            }
            elseif ($user->getRole() == UserRole::ROLE_USER)
            {
                $url = $this->container->get('templating.helper.assets')->getUrl("images/user-profile/user-photo-big-placeholder.png");
            }
            else
            {
                $url = $this->container->get('templating.helper.assets')->getUrl("images/teacher-page/teacher-photo-big-placeholder.png");
            }

            return $url;
        }

        public function getUserPhotoSmallUrl(User $user)
        {
            $url = "";
            if ($user->getHasPhoto())
            {
                $url = $user->getPhotoUrl();
            }
            elseif ($user->getRole() == UserRole::ROLE_USER)
            {
                $url = $this->container->get('templating.helper.assets')->getUrl("images/user-profile/user-photo-small-placeholder.png");
            }
            else
            {
                $url = $this->container->get('templating.helper.assets')->getUrl("images/teacher-page/teacher-photo-small-placeholder.png");
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
