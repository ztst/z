<?
    namespace Znaika\FrontendBundle\Repository\Profile\Action;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddBirthdayInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddClassroomInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddPhoneNumberInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddSchoolInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddSexInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddVideoCommentOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\BaseUserOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\JoinSocialNetworkCommunityOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\RateVideoOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\RegistrationOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\RegistrationReferralOperation;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class UserOperationRepository extends BaseRepository implements IUserOperationRepository
    {
        /**
         * @var IUserOperationRepository
         */
        protected $dbRepository;

        /**
         * @var IUserOperationRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new UserOperationRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Profile\Action\BaseUserOperation');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        public function save(BaseUserOperation $operation)
        {
            $this->redisRepository->save($operation);
            $success = $this->dbRepository->save($operation);

            return $success;
        }

        public function getLastAddCityInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddCityInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddCityInProfileOperation($user);
            }

            return $result;
        }

        public function getLastViewVideoOperation(User $user, Video $video)
        {
            $result = $this->redisRepository->getLastViewVideoOperation($user, $video);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastViewVideoOperation($user, $video);
            }

            return $result;
        }

        public function getLastPostVideoToSocialNetworkOperation(User $user, Video $video, $network)
        {
            $result = $this->redisRepository->getLastPostVideoToSocialNetworkOperation($user, $video, $network);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastPostVideoToSocialNetworkOperation($user, $video, $network);
            }

            return $result;
        }

        public function getLastAddPhoneNumberInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddPhoneNumberInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddPhoneNumberInProfileOperation($user);
            }

            return $result;
        }

        public function getLastAddBirthdayInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddBirthdayInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddBirthdayInProfileOperation($user);
            }

            return $result;
        }

        public function getLastAddClassroomInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddClassroomInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddClassroomInProfileOperation($user);
            }

            return $result;
        }

        public function getLastAddSchoolInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddSchoolInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddSchoolInProfileOperation($user);
            }

            return $result;
        }

        public function getLastAddSexInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddSexInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddSexInProfileOperation($user);
            }

            return $result;
        }

        public function getLastRegistrationReferralOperation(User $user)
        {
            $result = $this->redisRepository->getLastRegistrationReferralOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastRegistrationReferralOperation($user);
            }

            return $result;
        }

        public function getLastRegistrationOperation(User $user)
        {
            $result = $this->redisRepository->getLastRegistrationOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastRegistrationOperation($user);
            }

            return $result;
        }

        public function getLastAddVideoCommentOperation(User $user, Video $video)
        {
            $result = $this->redisRepository->getLastAddVideoCommentOperation($user, $video);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddVideoCommentOperation($user, $video);
            }

            return $result;
        }

        public function getLastRateVideoOperation(User $user, Video $video)
        {
            $result = $this->redisRepository->getLastRateVideoOperation($user, $video);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastRateVideoOperation($user, $video);
            }

            return $result;
        }

        public function getLastJoinSocialNetworkCommunityOperation(User $user, $network)
        {
            $result = $this->redisRepository->getLastJoinSocialNetworkCommunityOperation($user, $network);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastJoinSocialNetworkCommunityOperation($user, $network);
            }

            return $result;
        }
    }