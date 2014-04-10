<?
    namespace Znaika\ProfileBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\ProfileBundle\Entity\Action\BaseUserOperation;
    use Znaika\ProfileBundle\Entity\Badge\LearnerBadge;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Repository\Action\UserOperationRepository;
    use Znaika\ProfileBundle\Repository\Badge\UserBadgeRepository;

    abstract class UserOperationHandler
    {
        /**
         * @var UserOperationRepository
         */
        protected $userOperationRepository;

        /**
         * @var UserBadgeRepository
         */
        protected $userBadgeRepository;

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
         * @param \Znaika\ProfileBundle\Repository\Badge\UserBadgeRepository $userBadgeRepository
         */
        public function __construct(UserOperationRepository $userOperationRepository, UserBadgeRepository $userBadgeRepository)
        {
            $this->userOperationRepository = $userOperationRepository;
            $this->userBadgeRepository     = $userBadgeRepository;
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
            $this->pointsBefore = $this->getUser()->getPoints();
        }

        private function postHandle()
        {
            $user = $this->getUser();
            $pointsBefore = $this->pointsBefore;
            $currentPoints = $user->getPoints();

            if ($pointsBefore < LearnerBadge::MIN_POINTS && $currentPoints >= LearnerBadge::MIN_POINTS)
            {
                $badge = new LearnerBadge();
                $badge->setUser($user);
                $this->userBadgeRepository->save($badge);
            }
        }
    }