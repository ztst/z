<?
    namespace Znaika\LikesBundle\Repository;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\LikesBundle\Entity\VideoLike;
    use Znaika\ProfileBundle\Entity\User;

    class VideoLikeDBRepository extends EntityRepository implements IVideoLikeRepository
    {
        /**
         * {@inheritdoc}
         */
        public function save(VideoLike $videoLike)
        {
            $this->getEntityManager()->persist($videoLike);
            $this->getEntityManager()->flush();
        }

        /**
         * {@inheritdoc}
         */
        public function getUserVideoLike(User $user, Video $video)
        {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder();

            $queryBuilder->select('vl')
               ->from('ZnaikaLikesBundle:VideoLike', 'vl')
               ->andWhere("vl.video = :video_id")
               ->setParameter('video_id', $video->getVideoId())
               ->andWhere("vl.user = :user_id")
               ->setParameter('user_id', $user->getUserId());

            return $queryBuilder->getQuery()->getOneOrNullResult();
        }

        /**
         * {@inheritdoc}
         */
        public function getUserLikedVideos(User $user)
        {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder();

            $queryBuilder->select('vl')
                         ->from('ZnaikaLikesBundle:VideoLike', 'vl')
                         ->andWhere("vl.user = :user_id")
                         ->setParameter('user_id', $user->getUserId());

            return $queryBuilder->getQuery()->getResult();
        }

        /**
         * @param VideoLike $videoLike
         *
         * @return bool
         */
        public function delete(VideoLike $videoLike)
        {
            $this->getEntityManager()->remove($videoLike);
            $this->getEntityManager()->flush();
        }
    }