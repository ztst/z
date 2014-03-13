<?
    namespace Znaika\FrontendBundle\Helper\Util\Profile;

    class UserSex
    {
        const MALE         = 1;
        const FEMALE       = 2;

        public static function getAvailableTypes()
        {
            $availableTypes = array(
                self::MALE,
                self::FEMALE
            );

            return $availableTypes;
        }

        public static function getAvailableTypesTexts()
        {
            $availableTypes = array(
                self::MALE         => "Мужской", //TODO: i18n
                self::FEMALE       => "Женский" //TODO: i18n
            );

            return $availableTypes;
        }
    }