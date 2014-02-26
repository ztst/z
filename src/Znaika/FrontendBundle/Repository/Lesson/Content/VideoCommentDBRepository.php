<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentUtil;

    class VideoCommentDBRepository extends EntityRepository implements IVideoCommentRepository
    {
        /**
         * @param VideoComment $videoComment
         *
         * @return mixed
         */
        public function save(VideoComment $videoComment)
        {
            $this->getEntityManager()->persist($videoComment);
            $this->getEntityManager()->flush();
        }

        /**
         * @param $videoCommentId
         *
         * @return VideoComment
         */
        public function getOneByVideoCommentId($videoCommentId)
        {
            return $this->findOneByVideoCommentId($videoCommentId);
        }

        /**
         * @param Video $video
         * @param $limit
         *
         * @return VideoComment[]
         */
        public function getLastVideoComments(Video $video, $limit)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('vc')
               ->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
               ->andWhere("vc.video = :video_id")
               ->setParameter('video_id', $video->getVideoId())
               ->addOrderBy('vc.createdTime', 'DESC')
               ->setMaxResults($limit);

            return $qb->getQuery()->getResult();
        }

        /**
         * @param Video $video
         * @param $offset
         * @param $limit
         *
         * @return VideoComment[]
         */
        public function getVideoComments($video, $offset, $limit)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('vc')
               ->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
               ->andWhere("vc.video = :video_id")
               ->setParameter('video_id', $video->getVideoId())
               ->addOrderBy('vc.createdTime', 'DESC')
               ->setFirstResult($offset)
               ->setMaxResults($limit);

            return $qb->getQuery()->getResult();
        }

        /**
         * @param $video
         *
         * @return VideoComment[]
         */
        public function getVideoNotAnsweredQuestionComments($video)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('vc')
               ->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
               ->andWhere("vc.video = :video_id")
               ->setParameter('video_id', $video->getVideoId())
               ->andWhere("vc.isAnswered = :is_answered")
               ->setParameter('is_answered', false)
               ->andWhere("vc.commentType = :comment_type")
               ->setParameter('comment_type', VideoCommentUtil::QUESTION)
               ->addOrderBy('vc.createdTime', 'DESC');

            return $qb->getQuery()->getResult();
        }
    }