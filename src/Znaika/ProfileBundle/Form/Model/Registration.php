<?
    namespace Znaika\ProfileBundle\Form\Model;

    use Symfony\Component\Validator\Constraints as Assert;

    use Znaika\ProfileBundle\Entity\User;

    class Registration
    {
        /**
         * @Assert\Type(type="Znaika\ProfileBundle\Entity\User")
         * @Assert\Valid()
         */
        protected $user;

        /**
         * @param User $user
         */
        public function setUser(User $user)
        {
            $this->user = $user;
        }

        /**
         * @return User
         */
        public function getUser()
        {
            return $this->user;
        }
    }