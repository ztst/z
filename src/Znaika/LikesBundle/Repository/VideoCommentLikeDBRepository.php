<?
    namespace Znaika\LikesBundle\Repository;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\LikesBundle\Entity\VideoCommentLike;
    use Znaika\ProfileBundle\Entity\User;

    class VideoCommentLikeDBRepository extends EntityRepository implements IVideoCommentLikeRepository
    {
        /**
         * {@inheritdoc}
         */
        public function save(VideoCommentLike $videoCommentLike)
        {
            $this->getEntityManager()->persist($videoCommentLike);
            $this->getEntityManager()->flush();
        }

        /**
         * @param VideoCommentLike $videoCommentLike
         *
         * @return bool
         */
        public function delete(VideoCommentLike $videoCommentLike)
        {
            $this->getEntityManager()->remove($videoCommentLike);
            $this->getEntityManager()->flush();
        }

        /**
         * {@inheritdoc}
         */
        public function getUserCommentLike(User $user, VideoComment $videoComment)
        {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder();

            $queryBuilder->select('vcl')
                         ->from('ZnaikaLikesBundle:VideoCommentLike', 'vcl')
                         ->andWhere("vcl.videoComment = :video_comment_id")
                         ->setParameter('video_comment_id', $videoComment->getVideoCommentId())
                         ->andWhere("vcl.user = :user_id")
                         ->setParameter('user_id', $user->getUserId());

            return $queryBuilder->getQuery()->getOneOrNullResult();
        }

        /**
         * {@inheritdoc}
         */
        public function getUserVideoLikedComments(User $user, Video $video)
        {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder();

            $queryBuilder->select('vcl')
                         ->from('ZnaikaLikesBundle:VideoCommentLike', 'vcl')
                         ->andWhere("vcl.user = :user_id")
                         ->setParameter('user_id', $user->getUserId());

            return $queryBuilder->getQuery()->getResult();
        }

    }