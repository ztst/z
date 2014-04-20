<?
    namespace Znaika\FrontendBundle\Helper\Util;

    class UserSearchAgeUtil
    {
        const MIN_AGE = 14;
        const MAX_AGE = 80;

        public static function getAvailableAgeFrom()
        {
            $availableAges = range(self::MIN_AGE, self::MAX_AGE - 1);

            return $availableAges;
        }

        public static function getAvailableAgeTo()
        {
            $availableAges = range(self::MIN_AGE + 1, self::MAX_AGE);

            return $availableAges;
        }
    }