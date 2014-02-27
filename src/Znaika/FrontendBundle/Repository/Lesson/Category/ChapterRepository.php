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
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Category\Chapter');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        public function getAll()
        {
            $result = $this->redisRepository->getAll();
            if (empty($result))
            {
                $result = $this->dbRepository->getAll();
            }

            return $result;
        }

        public function getOneById($chapterId)
        {
            $result = $this->redisRepository->getOneById($chapterId);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneById($chapterId);
            }

            return $result;
        }

        public function getChaptersForCatalog($grade, $subjectId)
        {
            $result = $this->redisRepository->getChaptersForCatalog($grade, $subjectId);
            if (is_null($result))
            {
                $result = $this->dbRepository->getChaptersForCatalog($grade, $subjectId);
            }

            return $result;
        }

        public function getOne($name, $grade, $subjectId)
        {
            $result = $this->redisRepository->getOne($name, $grade, $subjectId);
            if (empty($result))
            {
                $result = $this->dbRepository->getOne($name, $grade, $subjectId);
            }

            return $result;
        }
    }