<?php
    namespace Znaika\FrontendBundle\Validator\Constraints;

    use Symfony\Component\HttpFoundation\File\UploadedFile;

    use Symfony\Component\Validator\Constraint;
    use Symfony\Component\Validator\ConstraintValidator;

    class UserPhotoValidator extends ConstraintValidator
    {
        public function validate($value, Constraint $constraint)
        {
            $isValid = true;
            if ($value instanceof UploadedFile)
            {
                $isValid = false;
                $imageSize = getimagesize($value);

                if ($imageSize !== false && is_array($imageSize))
                {
                    $isValid = true;
                    /** @var UserPhoto $constraint */
                    if ($imageSize[0] < $constraint->minSize || $imageSize[1] < $constraint->minSize)
                    {
                        $isValid = false;
                    }
                    if ($imageSize[0] > $constraint->maxSize || $imageSize[1] > $constraint->maxSize)
                    {
                        $isValid = false;
                    }
                    if ($imageSize[0] / $imageSize[1] > $constraint->sidesProp)
                    {
                        $isValid = false;
                    }
                    if ($imageSize[1] / $imageSize[0] > $constraint->sidesProp)
                    {
                        $isValid = false;
                    }
                }
            }

            if (!$isValid)
            {
                $this->context->addViolation($constraint->message);
            }

            return $isValid;
        }
    }