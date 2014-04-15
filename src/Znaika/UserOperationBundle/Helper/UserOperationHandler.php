<?
    namespace Znaika\UserOperationBundle\Helper;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\UserOperationBundle\Entity\BaseUserOperation;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\UserOperationBundle\Repository\UserOperationRepository;

    abstract class UserOperationHandler
    {
        /**
         * @var UserOperationRepository
         */
        protected $userOperationRepository;

        /**
         * @var integer
         */
        protected $socialNetwork;

        /**
         * @var User
         */
        protected $user;

        /**
         * @var Video
         */
        protected $video;

        /**
         * @var integer
         */
        private $pointsBefore;

        /**
         * @param UserOperationRepository $userOperationRepository
         */
        public function __construct(UserOperationRepository $userOperationRepository)
        {
            $this->userOperationRepository = $userOperationRepository;
        }

        /**
         * @return BaseUserOperation
         */
        public function handle()
        {
            $this->preHandle();
            $operation = $this->doHandle();
            $this->postHandle();

            return $operation;
        }

        /**
         * @param \Znaika\ProfileBundle\Entity\User $user
         */
        public function setUser($user)
        {
            $this->user = $user;
        }

        /**
         * @return \Znaika\ProfileBundle\Entity\User
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $video
         */
        public function setVideo($video)
        {
            $this->video = $video;
        }

        /**
         * @param int $socialNetwork
         */
        public function setSocialNetwork($socialNetwork)
        {
            $this->socialNetwork = $socialNetwork;
        }

        /**
         * @return int
         */
        public function getSocialNetwork()
        {
            return $this->socialNetwork;
        }

        /**
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\Video
         */
        public function getVideo()
        {
            return $this->video;
        }

        abstract protected function doHandle();

        private function preHandle()
        {
        }

        private function postHandle()
        {
        }
    }