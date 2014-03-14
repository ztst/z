<?
    namespace Znaika\FrontendBundle\Helper\Util\Lesson;

    class SubjectUtil
    {
        const MATEMATIKA      = 'matematika';
        const ALGEBRA         = 'algebra';
        const GEOMETRY        = 'geometry';
        const INFORMATIKA     = 'informatika';
        const PHYSICS         = 'physics';
        const CHEMISTRY       = 'chemistry';
        const OKRUJ_MIR       = 'okruj-mir';
        const PRIRODOVEDENIE  = 'prirodovedenie';
        const BIOLOGY         = 'biology';
        const GEOGRAFY        = 'geografy';
        const CHTENIE         = 'chtenie';
        const LITERATURA      = 'literatura';
        const RUSSIAN         = 'russian';
        const ENGLISH         = 'english';
        const OBSHESTVOZNANIE = 'obshestvoznanie';
        const OBZH            = 'obzh';
        const ISTORIYA_ROSSII = 'istoriya-rossii';
        const ISTORIA         = 'istoriya';
        const ESTESTVOZNANIE  = 'estesvoznanie';

        public static function getGradesSubjects()
        {
            return array(
                1  => self::getFirstGradeSubjects(),
                2  => self::getSecondGradeSubjects(),
                3  => self::getThirdGradeSubjects(),
                4  => self::getFourthGradeSubjects(),
                5  => self::getFifthGradeSubjects(),
                6  => self::getSixthGradeSubjects(),
                7  => self::getSeventhGradeSubjects(),
                8  => self::getEighthGradeSubjects(),
                9  => self::getNinthGradeSubjects(),
                10 => self::getTenthGradeSubjects(),
                11 => self::getEleventhGradeSubjects(),
            );
        }

        private static function getFirstGradeSubjects()
        {
            return array(
                self::MATEMATIKA,
                self::RUSSIAN,
                self::OKRUJ_MIR,
                self::CHTENIE
            );
        }

        private static function getSecondGradeSubjects()
        {
            return array(
                self::MATEMATIKA,
                self::RUSSIAN,
                self::OKRUJ_MIR,
                self::ENGLISH,
                self::CHTENIE
            );
        }

        private static function getThirdGradeSubjects()
        {
            return array(
                self::MATEMATIKA,
                self::RUSSIAN,
                self::OKRUJ_MIR,
                self::ENGLISH,
                self::CHTENIE
            );
        }

        private static function getFourthGradeSubjects()
        {
            return array(
                self::MATEMATIKA,
                self::RUSSIAN,
                self::OKRUJ_MIR,
                self::ENGLISH,
                self::CHTENIE
            );
        }

        private static function getFifthGradeSubjects()
        {
            return array(
                self::MATEMATIKA,
                self::RUSSIAN,
                self::ENGLISH,
                self::ISTORIA,
                self::LITERATURA,
                self::PRIRODOVEDENIE,
                self::INFORMATIKA,
                self::OBZH,
                self::OBZH,
                self::ESTESTVOZNANIE,
            );
        }

        private static function getSixthGradeSubjects()
        {
            return array(
                self::MATEMATIKA,
                self::RUSSIAN,
                self::ENGLISH,
                self::ISTORIA,
                self::GEOGRAFY,
                self::BIOLOGY,
                self::LITERATURA,
                self::OBSHESTVOZNANIE,
                self::INFORMATIKA,
                self::ISTORIYA_ROSSII,
                self::OBZH,
                self::ESTESTVOZNANIE,
            );
        }

        private static function getSeventhGradeSubjects()
        {
            return array(
                self::ALGEBRA,
                self::RUSSIAN,
                self::GEOMETRY,
                self::ENGLISH,
                self::PHYSICS,
                self::ISTORIA,
                self::GEOGRAFY,
                self::BIOLOGY,
                self::LITERATURA,
                self::INFORMATIKA,
                self::OBSHESTVOZNANIE,
                self::ISTORIYA_ROSSII,
                self::OBZH,
            );
        }

        private static function getEighthGradeSubjects()
        {
            return array(
                self::ALGEBRA,
                self::RUSSIAN,
                self::ENGLISH,
                self::CHEMISTRY,
                self::PHYSICS,
                self::GEOMETRY,
                self::ISTORIA,
                self::BIOLOGY,
                self::GEOGRAFY,
                self::LITERATURA,
                self::INFORMATIKA,
                self::OBSHESTVOZNANIE,
                self::ISTORIYA_ROSSII,
                self::OBZH,
            );
        }

        private static function getNinthGradeSubjects()
        {
            return array(
                self::ALGEBRA,
                self::GEOMETRY,
                self::RUSSIAN,
                self::PHYSICS,
                self::ENGLISH,
                self::CHEMISTRY,
                self::ISTORIA,
                self::GEOGRAFY,
                self::BIOLOGY,
                self::LITERATURA,
                self::INFORMATIKA,
                self::OBSHESTVOZNANIE,
                self::ISTORIYA_ROSSII,
                self::OBZH,
            );
        }

        private static function getTenthGradeSubjects()
        {
            return array(
                self::ALGEBRA,
                self::PHYSICS,
                self::RUSSIAN,
                self::GEOMETRY,
                self::ENGLISH,
                self::ISTORIA,
                self::CHEMISTRY,
                self::GEOGRAFY,
                self::BIOLOGY,
                self::OBSHESTVOZNANIE,
                self::LITERATURA,
                self::INFORMATIKA,
                self::ISTORIYA_ROSSII,
                self::OBZH,
            );
        }

        private static function getEleventhGradeSubjects()
        {
            return array(
                self::ALGEBRA,
                self::RUSSIAN,
                self::GEOMETRY,
                self::PHYSICS,
                self::ENGLISH,
                self::ISTORIA,
                self::CHEMISTRY,
                self::BIOLOGY,
                self::OBSHESTVOZNANIE,
                self::LITERATURA,
                self::INFORMATIKA,
                self::ISTORIYA_ROSSII,
                self::OBZH,
            );
        }
    }