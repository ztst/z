<?
    namespace Znaika\FrontendBundle\Helper\Util;

    class SocialNetworkUtil
    {
        const VK            = 1;
        const ODNOKLASSNIKI = 2;
        const FACEBOOK      = 3;
        const TWITTER       = 4;

        /**
         * @return array
         */
        public static function getAvailableSocialNetworks()
        {
            $availableClasses = array(
                self::VK,
                self::ODNOKLASSNIKI,
                self::FACEBOOK,
                self::TWITTER,
            );

            return $availableClasses;
        }

        /**
         * @param $network
         *
         * @return bool
         */
        public static function isValidSocialNetwork($network)
        {
            $availableClasses = self::getAvailableSocialNetworks();

            return in_array($network, $availableClasses);
        }
    }