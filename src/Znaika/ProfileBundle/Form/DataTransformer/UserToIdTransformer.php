<?
    namespace Znaika\ProfileBundle\Form\DataTransformer;

    use Symfony\Component\Form\DataTransformerInterface;
    use Symfony\Component\Form\Exception\UnexpectedTypeException;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class UserToIdTransformer implements DataTransformerInterface
    {
        /**
         * @var UserRepository
         */
        protected $userRepository;

        public function __construct(UserRepository $userRepository)
        {
            $this->userRepository = $userRepository;
        }

        /**
         * @param User|null $value
         *
         * @return string|null
         *
         * @throws UnexpectedTypeException if the given value is not a User instance
         */
        public function transform($value)
        {
            if (null === $value)
            {
                return null;
            }

            if (!$value instanceof User)
            {
                throw new UnexpectedTypeException($value, 'Znaika\ProfileBundle\Entity\User');
            }

            return $value->getUserId();
        }

        /**
         * @param string $value
         *
         * @return User
         *
         * @throws UnexpectedTypeException
         */
        public function reverseTransform($value)
        {
            if (null === $value || '' === $value)
            {
                return null;
            }

            $value = intval($value);
            if (!is_numeric($value))
            {
                throw new UnexpectedTypeException($value, 'number');
            }

            return $this->userRepository->getOneByUserId($value);
        }
    }