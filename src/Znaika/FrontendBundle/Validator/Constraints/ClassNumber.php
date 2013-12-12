<?
    namespace Znaika\FrontendBundle\Validator\Constraints;

    use Symfony\Component\Validator\Constraint;

    class ClassNumber extends Constraint
    {
        public $message = 'Класс "%string%" не существует.';
    }