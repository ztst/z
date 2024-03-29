<?
    namespace Znaika\ProfileBundle\Helper\Util;

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

        public static function getTextBySex($sex)
        {
            $availableTypes = self::getAvailableTypesTexts();

            return isset($availableTypes[$sex]) ? $availableTypes[$sex] : "-";
        }
    }