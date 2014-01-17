<?
    namespace Znaika\FrontendBundle\Helper\Util\Profile;

    class UserSex
    {
        const NOT_SELECTED = 0;
        const MALE         = 1;
        const FEMALE       = 2;

        public static function getAvailableTypes()
        {
            $availableTypes = array(
                self::NOT_SELECTED,
                self::MALE,
                self::FEMALE
            );

            return $availableTypes;
        }

        public static function getAvailableTypesTexts()
        {
            $availableTypes = array(
                self::NOT_SELECTED => "", //TODO: i18n
                self::MALE         => "Мужской", //TODO: i18n
                self::FEMALE       => "Женский" //TODO: i18n
            );

            return $availableTypes;
        }
    }