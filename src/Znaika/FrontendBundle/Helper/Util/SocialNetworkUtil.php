<?
    namespace Znaika\FrontendBundle\Helper\Util;

    use Znaika\ProfileBundle\Entity\User;

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

        /**
         * @param User $user
         * @param $socialType
         * @param $socialUserInfo
         */
        public static function addUserSocialInfo($user, $socialType, $socialUserInfo)
        {
            switch ($socialType)
            {
                case SocialNetworkUtil::VK:
                    $user->setVkId($socialUserInfo['uid']);
                    break;
                case SocialNetworkUtil::ODNOKLASSNIKI:
                    $user->setOdnoklassnikiId($socialUserInfo['uid']);
                    break;
                case SocialNetworkUtil::FACEBOOK:
                    $user->setFacebookId($socialUserInfo['id']);
                    break;
            }

            if(!$user->getFirstName() && isset($socialUserInfo['first_name']))
            {
                $user->setFirstName($socialUserInfo['first_name']);
            }
            if(!$user->getLastName() && isset($socialUserInfo['last_name']))
            {
                $user->setLastName($socialUserInfo['last_name']);
            }
        }
    }