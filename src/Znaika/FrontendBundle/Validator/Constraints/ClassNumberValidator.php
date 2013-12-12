<?
    namespace Znaika\FrontendBundle\Validator\Constraints;

    use Symfony\Component\Validator\Constraint;
    use Symfony\Component\Validator\ConstraintValidator;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;

    class ClassNumberValidator extends ConstraintValidator
    {
        public function validate($value, Constraint $constraint)
        {
            if ( !ClassNumberUtil::isValidClassNumber(intval($value)) )
            {
                $this->context->addViolation($constraint->message, array('%string%' => $value));
            }
        }
    }