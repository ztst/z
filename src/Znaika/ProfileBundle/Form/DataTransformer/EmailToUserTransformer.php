<?
    namespace Znaika\ProfileBundle\Form\DataTransformer;

    use Symfony\Component\Form\DataTransformerInterface;
    use Symfony\Component\Form\Exception\UnexpectedTypeException;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class EmailToUserTransformer implements DataTransformerInterface
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
         * @param string|null $value
         *
         * @return User|null
         *
         * @throws UnexpectedTypeException if the given value is not a string instance
         */
        public function reverseTransform($value)
        {
            if (null === $value)
            {
                return null;
            }

            if (!is_string($value))
            {
                throw new UnexpectedTypeException($value, 'string');
            }

            return $this->userRepository->getOneByEmail($value);
        }

        /**
         * @param User $value
         *
         * @return string
         *
         * @throws UnexpectedTypeException
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

            return $value->getEmail();
        }
    }