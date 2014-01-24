<?php

    namespace Znaika\FrontendBundle\Twig;

    use Znaika\FrontendBundle\Helper\Util\SocialNetworkUtil;

    class SocialNetworksExtension extends \Twig_Extension
    {
        public function __construct()
        {
        }

        public function getFunctions()
        {
            return array(
                'vk_button_id'            => new \Twig_Function_Method($this, 'renderVKButtonId'),
                'facebook_button_id'      => new \Twig_Function_Method($this, 'renderFacebookButtonId'),
                'odnoklassniki_button_id' => new \Twig_Function_Method($this, 'renderOdnoklassnikiButtonId'),
                'twitter_button_id'       => new \Twig_Function_Method($this, 'renderTwitterButtonId'),
            );
        }

        public function renderFacebookButtonId($prefix)
        {
            return $this->renderSocialButtonId($prefix, SocialNetworkUtil::FACEBOOK);
        }

        public function renderOdnoklassnikiButtonId($prefix)
        {
            return $this->renderSocialButtonId($prefix, SocialNetworkUtil::ODNOKLASSNIKI);
        }

        public function renderTwitterButtonId($prefix)
        {
            return $this->renderSocialButtonId($prefix, SocialNetworkUtil::TWITTER);
        }

        public function renderVKButtonId($prefix)
        {
            return $this->renderSocialButtonId($prefix, SocialNetworkUtil::VK);
        }

        /**
         * @param $prefix
         * @param $type
         *
         * @return string
         */
        public function renderSocialButtonId($prefix, $type)
        {
            $result = $prefix . $type;

            return $result;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_social_networks_extension';
        }
    }
