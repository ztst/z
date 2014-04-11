<?
    namespace Znaika\LikesBundle\Repository;

    use Doctrine\ORM\EntityManager;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\LikesBundle\Entity\VideoCommentLike;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class VideoCommentLikeRepository extends BaseRepository implements IVideoCommentLikeRepository
    {
        /**
         * @var IVideoCommentLikeRepository
         */
        protected $dbRepository;

        /**
         * @var IVideoCommentLikeRepository
         */
        protected $redisRepository;

        public function __construct(EntityManager $doctrine)
        {
            $redisRepository = new VideoCommentLikeRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaLikesBundle:VideoCommentLike');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * {@inheritdoc}
         */
        public function save(VideoCommentLike $videoCommentLike)
        {
            $this->redisRepository->save($videoCommentLike);
            $success = $this->dbRepository->save($videoCommentLike);

            return $success;
        }

        /**
         * @param VideoCommentLike $videoCommentLike
         *
         * @return bool
         */
        public function delete(VideoCommentLike $videoCommentLike)
        {
            $this->redisRepository->delete($videoCommentLike);
            $success = $this->dbRepository->delete($videoCommentLike);

            return $success;
        }

        /**
         * {@inheritdoc}
         */
        public function getUserCommentLike(User $user, VideoComment $videoComment)
        {
            $result = $this->redisRepository->getUserCommentLike($user, $videoComment);
            if (is_null($result))
            {
                $result = $this->dbRepository->getUserCommentLike($user, $videoComment);
            }

            return $result;
        }

        /**
         * {@inheritdoc}
         */
        public function getUserVideoLikedComments(User $user, Video $video)
        {
            $result = $this->redisRepository->getUserVideoLikedComments($user, $video);
            if (is_null($result))
            {
                $result = $this->dbRepository->getUserVideoLikedComments($user, $video);
            }

            return $result;
        }
    }