<?
    namespace Znaika\FrontendBundle\Helper\Util\Profile;

    use Znaika\FrontendBundle\Entity\Profile\User;

    class UserBan
    {
        const COMMENT_BAN_TIME = "P1D";

        const NO_REASON   = 0;
        const PROFILE     = 1;
        const COMMENT     = 2;
        const PERMANENTLY = 3;

        public static function getAvailableTypesTexts()
        {
            $availableTypes = array(
                self::PROFILE => "Профиль",
                self::COMMENT => "Комментарий"
            );

            return $availableTypes;
        }

        public static function isBanned(User $user)
        {
            return UserStatus::isBanned($user->getStatus());
        }

        public static function isPermanentlyBanned(User $user)
        {
            return UserStatus::isBanned($user->getStatus()) && $user->getBanReason() == UserBan::PERMANENTLY;
        }

        public static function isProfileBanned(User $user)
        {
            return UserStatus::isBanned($user->getStatus()) && $user->getBanReason() == UserBan::PROFILE;
        }
    }