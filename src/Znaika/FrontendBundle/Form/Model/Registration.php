<?
    namespace Znaika\FrontendBundle\Form\Model;

    use Symfony\Component\Validator\Constraints as Assert;

    use Znaika\FrontendBundle\Entity\Profile\User;

    class Registration
    {
        /**
         * @Assert\Type(type="Znaika\FrontendBundle\Entity\Profile\User")
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