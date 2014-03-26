<?
    namespace Znaika\FrontendBundle\Helper\Util\Profile;

    class UserStatus
    {
        const REGISTERED   = 1;
        const ACTIVE       = 2;
        const BANNED       = 3;
        const NOT_VERIFIED = 4;

        public static function getActiveStatuses()
        {
            return array(
                self::NOT_VERIFIED,
                self::ACTIVE
            );
        }

        public static function isActive($status)
        {
            return in_array($status, self::getActiveStatuses());
        }

        public static function isBanned($status)
        {
            return $status == self::BANNED;
        }
    }