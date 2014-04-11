<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentStatus;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentUtil;
    use Znaika\ProfileBundle\Helper\Util\UserRole;

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

        public function getByVideoCommentIds($videoCommentIds)
        {
            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->select('vc')
               ->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
               ->where($qb->expr()->in('vc.videoCommentId', $videoCommentIds));

            return $qb->getQuery()->getResult();
        }

        public function getLastVideoComments(Video $video, $limit)
        {
            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->select('vc')
               ->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
               ->andWhere("vc.video = :video_id")
               ->setParameter('video_id', $video->getVideoId())
               ->andWhere("vc.commentType != :comment_type")
               ->setParameter('comment_type', VideoCommentUtil::ANSWER)
               ->addOrderBy('vc.createdTime', 'DESC')
               ->setMaxResults($limit);

            return $qb->getQuery()->getResult();
        }

        public function getVideoComments($video, $offset, $limit)
        {
            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->select('vc')
               ->setFirstResult($offset)
               ->setMaxResults($limit);

            $this->prepareGetCommentsQueryBuilder($video, $qb);

            return $qb->getQuery()->getResult();
        }

        public function countVideoComments(Video $video)
        {
            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->select('count(vc)');
            $this->prepareGetCommentsQueryBuilder($video, $qb);

            return $qb->getQuery()->getSingleScalarResult();
        }

        public function getVideoNotAnsweredQuestionComments(Video $video)
        {
            $qb = $this->getEntityManager()->createQueryBuilder();

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

        public function getVideoNotVerifiedComments(Video $video)
        {
            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->select('vc')
               ->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
               ->andWhere("vc.video = :video_id")
               ->setParameter('video_id', $video->getVideoId())
               ->andWhere("vc.status = :not_verified")
               ->setParameter('not_verified', VideoCommentStatus::NOT_VERIFIED)
               ->addOrderBy('vc.createdTime', 'ASC');

            return $qb->getQuery()->getResult();
        }

        public function getTeacherNotAnsweredQuestionComments(User $user)
        {
            $qb = $this->getTeacherNotAnsweredQuestionCommentsQueryBuilder($user);
            $qb->select('vc');

            return $qb->getQuery()->getResult();
        }

        public function countTeacherNotAnsweredQuestionComments(User $user)
        {
            $qb = $this->getTeacherNotAnsweredQuestionCommentsQueryBuilder($user);
            $qb->select('count(vc)');

            return $qb->getQuery()->getSingleScalarResult();
        }

        public function getModeratorNotVerifiedComments()
        {
            $qb = $this->getModeratorNotVerifiedCommentsQueryBuilder();
            $qb->select('vc');

            return $qb->getQuery()->getResult();
        }

        public function countModeratorNotVerifiedComments()
        {
            $qb = $this->getModeratorNotVerifiedCommentsQueryBuilder();
            $qb->select('count(vc)');

            return $qb->getQuery()->getSingleScalarResult();
        }

        private function getModeratorNotVerifiedCommentsQueryBuilder()
        {
            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
               ->andWhere("vc.status = :not_verified")
               ->setParameter('not_verified', VideoCommentStatus::NOT_VERIFIED)
               ->addOrderBy('vc.createdTime', 'DESC');

            return $qb;
        }

        private function getTeacherNotAnsweredQuestionCommentsQueryBuilder(User $user)
        {
            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
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

            return $qb;
        }

        /**
         * @param $video
         * @param $qb
         */
        private function prepareGetCommentsQueryBuilder($video, $qb)
        {
            $qb->from('ZnaikaFrontendBundle:Lesson\Content\VideoComment', 'vc')
               ->andWhere("vc.video = :video_id")
               ->setParameter('video_id', $video->getVideoId())
               ->andWhere("vc.commentType != :comment_type")
               ->setParameter('comment_type', VideoCommentUtil::ANSWER)
               ->addOrderBy('vc.createdTime', 'DESC');
        }
    }