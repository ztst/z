<?
    namespace Znaika\UserOperationBundle\Repository;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\UserOperationBundle\Entity\AddPhotoInProfileOperation;
    use Znaika\UserOperationBundle\Entity\BaseUserOperation;
    use Znaika\ProfileBundle\Entity\User;

    class UserOperationDBRepository extends EntityRepository implements IUserOperationRepository
    {
        public function save(BaseUserOperation $operation)
        {
            $this->getEntityManager()->persist($operation);
            $this->getEntityManager()->flush();
        }

        public function getLastViewVideoOperation(User $user, Video $video)
        {
            return $this->getLastOperationByUserAndVideo($user, $video, 'ZnaikaUserOperationBundle:ViewVideoOperation');
        }

        public function getLastAddFirstNameInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaUserOperationBundle:AddFirstNameInProfileOperation');
        }

        public function getLastAddPhotoInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaUserOperationBundle:AddPhotoInProfileOperation');
        }

        public function getLastAddLastNameInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaUserOperationBundle:AddLastNameInProfileOperation');
        }

        public function getLastAddCityInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaUserOperationBundle:AddCityInProfileOperation');
        }

        public function getLastAddBirthdayInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaUserOperationBundle:AddBirthdayInProfileOperation');
        }

        public function getLastAddSexInProfileOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaUserOperationBundle:AddSexInProfileOperation');
        }

        public function getLastRegistrationOperation(User $user)
        {
            return $this->getLastOperationByUser($user, 'ZnaikaUserOperationBundle:RegistrationOperation');
        }

        public function getLastAddVideoCommentOperation(User $user, Video $video)
        {
            return $this->getLastOperationByUserAndVideo($user, $video, 'ZnaikaUserOperationBundle:AddVideoCommentOperation');
        }

        public function getLastRateVideoOperation(User $user, Video $video)
        {
            return $this->getLastOperationByUserAndVideo($user, $video, 'ZnaikaUserOperationBundle:RateVideoOperation');
        }

        public function getLastJoinSocialNetworkCommunityOperation(User $user, $network)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('uo')
               ->from('ZnaikaUserOperationBundle:JoinSocialNetworkCommunityOperation', 'uo')
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
               ->from('ZnaikaUserOperationBundle:ViewVideoOperation', 'uo')
               ->andWhere('uo.video = :video_id')
               ->setParameter('video_id', $video->getVideoId());

            return intval($qb->getQuery()->getSingleScalarResult());
        }

        public function getLastPostVideoToSocialNetworkOperation(User $user, Video $video, $network)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('uo')
               ->from('ZnaikaUserOperationBundle:PostVideoToSocialNetworkOperation', 'uo')
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
            return $this->countUserOperations($user, 'ZnaikaUserOperationBundle:ViewVideoOperation');
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countAddVideoCommentOperations(User $user)
        {
            return $this->countUserOperations($user, 'ZnaikaUserOperationBundle:AddVideoCommentOperation');
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countRateVideoOperations(User $user)
        {
            return $this->countUserOperations($user, 'ZnaikaUserOperationBundle:RateVideoOperation');
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countPostVideoToSocialNetworkOperations(User $user)
        {
            return $this->countUserOperations($user, 'ZnaikaUserOperationBundle:PostVideoToSocialNetworkOperation');
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
               ->from('ZnaikaUserOperationBundle:BaseUserOperation', 'uo')
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