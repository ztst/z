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
         * @Assert\NotBlank()
         * @Assert\True()
         */
        protected $termsAccepted;

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

        public function getTermsAccepted()
        {
            return $this->termsAccepted;
        }

        public function setTermsAccepted($termsAccepted)
        {
            $this->termsAccepted = (Boolean)$termsAccepted;
        }
    }