<?
    namespace Znaika\LikesBundle\Repository;

    use Doctrine\ORM\EntityManager;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\LikesBundle\Entity\VideoLike;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class VideoLikeRepository extends BaseRepository implements IVideoLikeRepository
    {
        /**
         * @var IVideoLikeRepository
         */
        protected $dbRepository;

        /**
         * @var IVideoLikeRepository
         */
        protected $redisRepository;

        public function __construct(EntityManager $doctrine)
        {
            $redisRepository = new VideoLikeRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaLikesBundle:VideoLike');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * {@inheritdoc}
         */
        public function save(VideoLike $videoLike)
        {
            $this->redisRepository->save($videoLike);
            $success = $this->dbRepository->save($videoLike);

            return $success;
        }

        /**
         * @param VideoLike $videoLike
         *
         * @return bool
         */
        public function delete(VideoLike $videoLike)
        {
            $this->redisRepository->delete($videoLike);
            $success = $this->dbRepository->delete($videoLike);

            return $success;
        }

        /**
         * {@inheritdoc}
         */
        public function getUserVideoLike(User $user, Video $video)
        {
            $result = $this->redisRepository->getUserVideoLike($user, $video);
            if (is_null($result))
            {
                $result = $this->dbRepository->getUserVideoLike($user, $video);
            }

            return $result;
        }

        /**
         * {@inheritdoc}
         */
        public function getUserLikedVideos(User $user)
        {
            $result = $this->redisRepository->getUserLikedVideos($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->getUserLikedVideos($user);
            }

            return $result;
        }

    }