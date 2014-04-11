<?
    namespace Znaika\ProfileBundle\Validator\Constraints;

    use Symfony\Component\Validator\Constraint;

    class UserPhoto extends Constraint
    {
        public $message = 'Неверный размер картинки.';

        public $minSize = 300;
        public $maxSize = 4000;
        public $sidesProp = 3;
    }