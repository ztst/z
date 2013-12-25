<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Znaika\FrontendBundle\Repository\BaseRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;

    class SubjectRepository extends BaseRepository implements ISubjectRepository
    {
        /**
         * @var ISubjectRepository
         */
        protected $dbRepository;

        /**
         * @var ISubjectRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new SubjectRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Category\Subject');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param $name
         *
         * @return Subject|null
         */
        public function getOneByUrlName($name)
        {
            $result = $this->redisRepository->getOneByUrlName($name);
            if ( empty($result) )
            {
                $result = $this->dbRepository->getOneByUrlName($name);
            }
            return $result;
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
    }