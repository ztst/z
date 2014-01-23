<?
    namespace Znaika\FrontendBundle\Repository\Profile\Action;

    use Doctrine\ORM\EntityRepository;
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

    class UserOperationDBRepository extends EntityRepository implements IUserOperationRepository
    {
        public function save(BaseUserOperation $operation)
        {
            $this->getEntityManager()->persist($operation);
            $this->getEntityManager()->flush();
        }

        public function getLastAddCityInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaFrontendBundle:Profile\Action\AddCityInProfileOperation');
        }

        public function getLastViewVideoOperation(User $user, Video $video)
        {
            return $this->getLastOperationByUserAndVideo($user, $video, 'ZnaikaFrontendBundle:Profile\Action\ViewVideoOperation');
        }

        public function getLastAddPhoneNumberInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaFrontendBundle:Profile\Action\AddPhoneNumberInProfileOperation');
        }

        public function getLastAddBirthdayInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaFrontendBundle:Profile\Action\AddBirthdayInProfileOperation');
        }

        public function getLastAddClassroomInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaFrontendBundle:Profile\Action\AddClassroomInProfileOperation');
        }

        public function getLastAddSchoolInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaFrontendBundle:Profile\Action\AddSchoolInProfileOperation');
        }

        public function getLastAddSexInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaFrontendBundle:Profile\Action\AddSexInProfileOperation');
        }

        public function getLastRegistrationReferralOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaFrontendBundle:Profile\Action\RegistrationReferralOperation');
        }

        public function getLastRegistrationOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaFrontendBundle:Profile\Action\RegistrationOperation');
        }

        public function getLastAddVideoCommentOperation(User $user, Video $video)
        {
            return $this->getLastOperationByUserAndVideo($user, $video, 'ZnaikaFrontendBundle:Profile\Action\AddVideoCommentOperation');
        }

        public function getLastRateVideoOperation(User $user, Video $video)
        {
            return $this->getLastOperationByUserAndVideo($user, $video, 'ZnaikaFrontendBundle:Profile\Action\RateVideoOperation');
        }

        public function getLastJoinSocialNetworkCommunityOperation(User $user, $network)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('uo')
               ->from('ZnaikaFrontendBundle:Profile\Action\JoinSocialNetworkCommunityOperation', 'uo')
               ->andWhere('uo.user = :user_id')
               ->setParameter('user_id', $user->getUserId())
               ->andWhere('uo.socialNetwork = :social_network')
               ->setParameter('social_network', $network)
               ->orderBy('uo.createdTime', 'DESC')
               ->setMaxResults(1);

            return $qb->getQuery()->getOneOrNullResult();
        }

        public function getViews(Video $video)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('count(uo.userOperationId)')
               ->from('ZnaikaFrontendBundle:Profile\Action\ViewVideoOperation', 'uo')
               ->andWhere('uo.video = :video_id')
               ->setParameter('video_id', $video->getVideoId());

            return intval($qb->getQuery()->getSingleScalarResult());
        }

        public function getLastPostVideoToSocialNetworkOperation(User $user, Video $video, $network)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('uo')
               ->from('ZnaikaFrontendBundle:Profile\Action\PostVideoToSocialNetworkOperation', 'uo')
               ->andWhere('uo.user = :user_id')
               ->setParameter('user_id', $user->getUserId())
               ->andWhere('uo.video = :video_id')
               ->setParameter('video_id', $video->getVideoId())
               ->andWhere('uo.socialNetwork = :social_network')
               ->setParameter('social_network', $network)
               ->orderBy('uo.createdTime', 'DESC')
               ->setMaxResults(1);

            return $qb->getQuery()->getOneOrNullResult();
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countViewVideoOperations(User $user)
        {
            return $this->countUserOperations($user, 'ZnaikaFrontendBundle:Profile\Action\ViewVideoOperation');
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countAddVideoCommentOperations(User $user)
        {
            return $this->countUserOperations($user, 'ZnaikaFrontendBundle:Profile\Action\AddVideoCommentOperation');
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countRateVideoOperations(User $user)
        {
            return $this->countUserOperations($user, 'ZnaikaFrontendBundle:Profile\Action\RateVideoOperation');
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countReferralRegistrationOperations(User $user)
        {
            return $this->countUserOperations($user, 'ZnaikaFrontendBundle:Profile\Action\ReferralRegistrationOperation');
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countPostVideoToSocialNetworkOperations(User $user)
        {
            return $this->countUserOperations($user, 'ZnaikaFrontendBundle:Profile\Action\PostVideoToSocialNetworkOperation');
        }

        /**
         * @param integer $limit
         *
         * @return BaseUserOperation[]
         */
        public function getNewestOperations($limit)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('uo')
               ->from('ZnaikaFrontendBundle:Profile\Action\BaseUserOperation', 'uo')
               ->orderBy('uo.createdTime', 'DESC')
               ->setMaxResults($limit);

            return $qb->getQuery()->getResult();
        }

        protected function getLastOperationByUser(User $user, $type)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('uo')
               ->from($type, 'uo')
               ->andWhere('uo.user = :user_id')
               ->setParameter('user_id', $user->getUserId())
               ->orderBy('uo.createdTime', 'DESC')
               ->setMaxResults(1);

            return $qb->getQuery()->getOneOrNullResult();
        }

        protected function getLastOperationByUserAndVideo(User $user, Video $video, $type)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('uo')
               ->from($type, 'uo')
               ->andWhere('uo.user = :user_id')
               ->setParameter('user_id', $user->getUserId())
               ->andWhere('uo.video = :video_id')
               ->setParameter('video_id', $video->getVideoId())
               ->orderBy('uo.createdTime', 'DESC')
               ->setMaxResults(1);

            return $qb->getQuery()->getOneOrNullResult();
        }

        /**
         * @param $user
         * @param $type
         *
         * @return int
         */
        protected function countUserOperations($user, $type)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('count(uo.userOperationId)')
               ->from($type, 'uo')
               ->andWhere('uo.user = :user_id')
               ->setParameter('user_id', $user->getUserId());

            return intval($qb->getQuery()->getSingleScalarResult());
        }
    }