<?
    namespace Znaika\FrontendBundle\Helper\Util;

    use Znaika\ProfileBundle\Helper\Util\UserRole;

    class MessageFilterUtil
    {
        const ALL     = 1;
        const PUPIL   = 2;
        const TEACHER = 3;
        const PARENT  = 4;

        /**
         * @return array
         */
        public static function getAvailableValues()
        {
            $availableValues = array(
                self::ALL,
                self::PUPIL,
                self::TEACHER,
                self::PARENT,
            );

            return $availableValues;
        }

        /**
         * @return array
         */
        public static function getAvailableValuesForSelect()
        {
            $availableValues = array(
                self::ALL     => "Все диалоги",
                self::PUPIL   => "Диалоги с учениками",
                self::TEACHER => "Диалоги с учителями",
                self::PARENT  => "Диалоги с родителями",
            );

            return $availableValues;
        }

        public static function getRoleByFilter($filter)
        {
            $filter = intval($filter);
            $availableValues = array(
                self::PUPIL   => UserRole::ROLE_USER,
                self::TEACHER => UserRole::ROLE_TEACHER,
                self::PARENT  => UserRole::ROLE_PARENT,
            );

            return isset($availableValues[$filter]) ? $availableValues[$filter] : null;
        }
    }