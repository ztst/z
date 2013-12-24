<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Znaika\FrontendBundle\Repository\BaseRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;

    class ChapterRepository extends BaseRepository implements IChapterRepository
    {
        /**
         * @var IChapterRepository
         */
        protected $dbRepository;

        /**
         * @var IChapterRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new ChapterRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Category\Chapter');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @return array|null
         */
        public function getAll()
        {
            $result = $this->redisRepository->getAll();
            if ( empty($result) )
            {
                $result = $this->dbRepository->getAll();
            }
            return $result;
        }

        /**
         * @param $chapterId
         *
         * @return null|Chapter
         */
        public function getOneById($chapterId)
        {
            $result = $this->redisRepository->getOneById($chapterId);
            if ( empty($result) )
            {
                $result = $this->dbRepository->getOneById($chapterId);
            }
            return $result;
        }
    }