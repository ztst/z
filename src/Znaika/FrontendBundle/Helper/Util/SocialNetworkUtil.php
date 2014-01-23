<?
    namespace Znaika\FrontendBundle\Helper\Util;

    class SocialNetworkUtil
    {
        const VK            = 1;
        const ODNOKLASSNIKI = 2;

        /**
         * @return array
         */
        public static function getAvailableSocialNetworks()
        {
            $availableClasses = array(
                self::VK,
                self::ODNOKLASSNIKI,
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