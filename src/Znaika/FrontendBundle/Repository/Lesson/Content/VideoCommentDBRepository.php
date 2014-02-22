<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;

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
    }