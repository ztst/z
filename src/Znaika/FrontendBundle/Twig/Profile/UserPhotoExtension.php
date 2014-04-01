<?php

    namespace Znaika\FrontendBundle\Twig\Profile;

    use Znaika\FrontendBundle\Entity\Profile\User;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;

    class UserPhotoExtension extends \Twig_Extension
    {
        const USER_BIG_PHOTO_URL      = "images/user-profile/user-photo-big-placeholder.png";
        const TEACHER_BIG_PHOTO_URL   = "images/teacher-page/teacher-photo-big-placeholder.png";
        const USER_SMALL_PHOTO_URL    = "images/user-profile/user-photo-small-placeholder.png";
        const TEACHER_SMALL_PHOTO_URL = "images/teacher-page/teacher-photo-small-placeholder.png";
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
                'user_photo_big_url'      => new \Twig_Function_Method($this, 'getUserPhotoBigUrl'),
                'default_photo_big_url'   => new \Twig_Function_Method($this, 'getDefaultPhotoBigUrl'),
                'default_photo_small_url' => new \Twig_Function_Method($this, 'getDefaultPhotoSmallUrl'),
                'user_photo_small_url'    => new \Twig_Function_Method($this, 'getUserPhotoSmallUrl')
            );
        }

        public function getUserPhotoBigUrl(User $user)
        {
            if ($user->getPhotoFileName())
            {
                $url = $user->getBigPhotoUrl();
            }
            else
            {
                $url = $this->getDefaultPhotoBigUrl($user);
            }

            return $url;
        }

        public function getUserPhotoSmallUrl(User $user)
        {
            if ($user->getPhotoFileName())
            {
                $url = $user->getSmallPhotoUrl();
            }
            else
            {
                $url = $this->getDefaultPhotoSmallUrl($user);
            }

            return $url;
        }

        public function getDefaultPhotoBigUrl(User $user)
        {
            if ($user->getRole() == UserRole::ROLE_USER)
            {
                $url = $this->container->get('templating.helper.assets')->getUrl(self::USER_BIG_PHOTO_URL);
            }
            else
            {
                $url = $this->container->get('templating.helper.assets')->getUrl(self::TEACHER_BIG_PHOTO_URL);
            }

            return $url;
        }

        public function getDefaultPhotoSmallUrl(User $user)
        {
            if ($user->getRole() == UserRole::ROLE_USER)
            {
                $url = $this->container->get('templating.helper.assets')->getUrl(self::USER_SMALL_PHOTO_URL);
            }
            else
            {
                $url = $this->container->get('templating.helper.assets')->getUrl(self::TEACHER_SMALL_PHOTO_URL);
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
