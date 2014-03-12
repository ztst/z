<?
    namespace Znaika\FrontendBundle\Form\DataTransformer;

    use Doctrine\Common\Collections\ArrayCollection;
    use Symfony\Component\Form\DataTransformerInterface;
    use Symfony\Component\Form\Exception\UnexpectedTypeException;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class EmailsToUsersTransformer implements DataTransformerInterface
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
         * @return ArrayCollection
         *
         * @throws UnexpectedTypeException if the given value is not a string instance
         */
        public function reverseTransform($value)
        {
            if (null === $value)
            {
                return new ArrayCollection();
            }

            if (!is_string($value))
            {
                throw new UnexpectedTypeException($value, 'string');
            }

            $users = new ArrayCollection();

            $emails = explode(",", $value);
            foreach($emails as $email)
            {
                $email = trim($email);
                $user = $this->userRepository->getOneByEmail($email);
                if ($user->getRole() == UserRole::ROLE_TEACHER)
                {
                    $users->add($user);
                }
            }

            return $users;
        }

        /**
         * @param ArrayCollection $value
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

            if (!$value instanceof \Doctrine\Common\Collections\Collection)
            {
                throw new UnexpectedTypeException($value, 'Collection');
            }

            $result = array();
            foreach($value as $user)
            {
                $result[] = $user->getEmail();
            }

            return implode(", ", $result);
        }
    }