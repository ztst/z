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
    }