<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentUtil;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;

    class VideoCommentDBRepository extends EntityRepository implements IVideoCommentRepository
    {
        public function save(VideoComment $videoComment)
        {
            $this->getEntityManager()->persist($videoComment);
            $this->getEntityManager()->flush();
        }

        public function getOneByVideoCommentId($videoCommentId)
        {
            return $this->findOneByVideoCommentId($videoCommentId);
        }

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

        public function getVideoNotAnsweredQuestionComments(Video $video)
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
               ->addOrderBy('vc.createdTime', 'ASC');

            return $qb->getQuery()->getResult();
        }

        /**
         * @param $user
         *
         * @return VideoComment[]
         */
        public function getTeacherNotAnsweredQuestionComments(User $user)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('vc')
               ->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
               ->andWhere("vc.isAnswered = :is_answered")
               ->setParameter('is_answered', false)
               ->andWhere("vc.commentType = :comment_type")
               ->setParameter('comment_type', VideoCommentUtil::QUESTION)
               ->addOrderBy('vc.createdTime', 'DESC');

            if ($user->getRole() == UserRole::ROLE_TEACHER)
            {
                $qb->innerJoin('vc.video', 'v')
                  ->innerJoin('v.supervisors', 's', 'WITH', 's.user = :user_id')
                  ->setParameter('user_id', $user->getUserId());
            }

            return $qb->getQuery()->getResult();
        }

        /**
         * @param $user
         *
         * @return int
         */
        public function countTeacherNotAnsweredQuestionComments(User $user)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('count(vc)')
               ->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
               ->andWhere("vc.isAnswered = :is_answered")
               ->setParameter('is_answered', false)
               ->andWhere("vc.commentType = :comment_type")
               ->setParameter('comment_type', VideoCommentUtil::QUESTION)
               ->addOrderBy('vc.createdTime', 'DESC');

            if ($user->getRole() == UserRole::ROLE_TEACHER)
            {
                $qb->innerJoin('vc.video', 'v')
                   ->innerJoin('v.supervisors', 's', 'WITH', 's.userId = :user_id')
                   ->setParameter('user_id', $user->getUserId());
            }

            return $qb->getQuery()->getSingleScalarResult();
        }
    }