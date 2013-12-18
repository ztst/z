<?
    namespace Znaika\FrontendBundle\Form\Model;

    use Symfony\Component\Validator\Constraints as Assert;

    use Znaika\FrontendBundle\Entity\User\UserInfo;

    class Registration
    {
        /**
         * @Assert\Type(type="Znaika\FrontendBundle\Entity\User\UserInfo")
         * @Assert\Valid()
         */
        protected $user;

        /**
         * @Assert\NotBlank()
         * @Assert\True()
         */
        protected $termsAccepted;

        /**
         * @param UserInfo $user
         */
        public function setUser(UserInfo $user)
        {
            $this->user = $user;
        }

        /**
         * @return UserInfo
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