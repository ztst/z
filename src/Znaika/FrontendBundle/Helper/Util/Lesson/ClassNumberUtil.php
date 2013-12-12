<?
namespace Znaika\FrontendBundle\Helper\Util\Lesson;

class ClassNumberUtil
{
    const MIN_CLASS_NUMBER = 1;
    const MAX_CLASS_NUMBER = 11;

    public static function getAvailableClasses()
    {
        $availableClasses = range(self::MIN_CLASS_NUMBER, self::MAX_CLASS_NUMBER);
        return $availableClasses;
    }

    public static function getAvailableClassesForSelect()
    {
        $availableClasses = self::getAvailableClasses();
        return array_combine($availableClasses, $availableClasses);
    }

    public static function isValidClassNumber($number)
    {
        $availableClasses = self::getAvailableClasses();
        return in_array($number, $availableClasses);
    }
}