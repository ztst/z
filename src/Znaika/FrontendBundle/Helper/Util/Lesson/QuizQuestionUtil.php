<?
    namespace Znaika\FrontendBundle\Helper\Util\Lesson;

    class QuizQuestionUtil
    {
        const CHOICE = 1;
        const MULTIPLE_CHOICE = 2;

        public static function getAvailableTypes()
        {
            $availableTypes = array(
                self::CHOICE,
                self::MULTIPLE_CHOICE
            );
            return $availableTypes;
        }

        public static function getAvailableTypesTexts()
        {
            $availableTypes = array(
                self::CHOICE          => "Одиночный выбор",    //TODO: i18n
                self::MULTIPLE_CHOICE => "Множественный выбор" //TODO: i18n
            );
            return $availableTypes;
        }

        public static function isValidType($type)
        {
            $availableTypes = self::getAvailableTypes();
            return in_array($type, $availableTypes);
        }
    }