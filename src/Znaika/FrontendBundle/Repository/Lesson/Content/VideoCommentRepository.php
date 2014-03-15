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
    }