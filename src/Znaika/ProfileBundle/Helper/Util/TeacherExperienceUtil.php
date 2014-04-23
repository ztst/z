<?
    namespace Znaika\ProfileBundle\Helper\Util;

    use Symfony\Component\Translation\Translator;

    class TeacherExperienceUtil
    {
        const MIN_EXPERIENCE = 0;
        const MAX_EXPERIENCE = 52;

        public static function getAvailableValues()
        {
            $availableValues = range(self::MIN_EXPERIENCE, self::MAX_EXPERIENCE);
            return $availableValues;
        }


        public static function getAvailableExperienceForSelect(Translator $translator)
        {
            $availableValues = self::getAvailableValues();

            $result = array();

            foreach ($availableValues as $value)
            {
                $result[$value] = $translator->transChoice(
                    "teacher_experience_choice",
                    $value,
                    array('%count%' => $value)
                );
            }

            return $result;
        }

        public static function getAvailableValuesFrom()
        {
            $availableAges = range(self::MIN_EXPERIENCE, self::MAX_EXPERIENCE - 1);

            return $availableAges;
        }

        public static function getAvailableValuesTo()
        {
            $availableAges = range(self::MIN_EXPERIENCE + 1, self::MAX_EXPERIENCE);

            return $availableAges;
        }
    }