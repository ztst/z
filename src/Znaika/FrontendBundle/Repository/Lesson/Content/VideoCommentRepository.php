<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class VideoCommentRepository extends BaseRepository implements IVideoCommentRepository
    {
        /**
         * @var IVideoCommentRepository
         */
        protected $dbRepository;

        /**
         * @var IVideoCommentRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new VideoCommentRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\VideoComment');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        public function save(VideoComment $videoComment)
        {
            $this->redisRepository->save($videoComment);
            $success = $this->dbRepository->save($videoComment);

            return $success;
        }

        public function getOneByVideoCommentId($videoCommentId)
        {
            $result = $this->redisRepository->getOneByVideoCommentId($videoCommentId);
            if (is_null($result))
            {
                $result = $this->dbRepository->getOneByVideoCommentId($videoCommentId);
            }

            return $result;
        }

        /**
         * @param $videoCommentIds
         *
         * @return VideoComment[]
         */
        public function getByVideoCommentIds($videoCommentIds)
        {
            $result = $this->redisRepository->getByVideoCommentIds($videoCommentIds);
            if (is_null($result))
            {
                $result = $this->dbRepository->getByVideoCommentIds($videoCommentIds);
            }

            return $result;
        }

        public function getLastVideoComments(Video $video, $limit)
        {
            $result = $this->redisRepository->getLastVideoComments($video, $limit);
            if (is_null($result))
            {
                $result = $this->dbRepository->getLastVideoComments($video, $limit);
            }

            return $result;
        }

        public function getVideoComments($video, $offset, $limit)
        {
            $result = $this->redisRepository->getVideoComments($video, $offset, $limit);
            if (is_null($result))
            {
                $result = $this->dbRepository->getVideoComments($video, $offset, $limit);
            }

            return $result;
        }

        public function countVideoComments(Video $video)
        {
            $result = $this->redisRepository->countVideoComments($video);
            if (is_null($result))
            {
                $result = $this->dbRepository->countVideoComments($video);
            }

            return $result;
        }

        public function getVideoNotAnsweredQuestionComments(Video $video)
        {
            $result = $this->redisRepository->getVideoNotAnsweredQuestionComments($video);
            if (is_null($result))
            {
                $result = $this->dbRepository->getVideoNotAnsweredQuestionComments($video);
            }

            return $result;
        }

        public function getTeacherNotAnsweredQuestionComments(User $user)
        {
            $result = $this->redisRepository->getTeacherNotAnsweredQuestionComments($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->getTeacherNotAnsweredQuestionComments($user);
            }

            return $result;
        }

        public function countTeacherNotAnsweredQuestionComments(User $user)
        {
            $result = $this->redisRepository->countTeacherNotAnsweredQuestionComments($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->countTeacherNotAnsweredQuestionComments($user);
            }

            return $result;
        }

        /**
         * @internal param $user
         *
         * @return VideoComment[]
         */
        public function getModeratorNotVerifiedComments()
        {
            $result = $this->redisRepository->getModeratorNotVerifiedComments();
            if (is_null($result))
            {
                $result = $this->dbRepository->getModeratorNotVerifiedComments();
            }

            return $result;
        }

        /**
         * @internal param $user
         *
         * @return int
         */
        public function countModeratorNotVerifiedComments()
        {
            $result = $this->redisRepository->countModeratorNotVerifiedComments();
            if (is_null($result))
            {
                $result = $this->dbRepository->countModeratorNotVerifiedComments();
            }

            return $result;
        }

        public function getVideoNotVerifiedComments(Video $video)
        {
            $result = $this->redisRepository->getVideoNotVerifiedComments($video);
            if (is_null($result))
            {
                $result = $this->dbRepository->getVideoNotVerifiedComments($video);
            }

            return $result;
        }
    }