<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    use Znaika\FrontendBundle\Entity\BaseRepository;

    class SynopsisRepository extends BaseRepository implements ISynopsisRepository
    {
        /**
         * @var ISynopsisRepository
         */
        protected $dbRepository;

        /**
         * @var ISynopsisRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new SynopsisRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\Synopsis');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param Synopsis $synopsis
         *
         * @return mixed
         */
        public function save(Synopsis $synopsis)
        {

            $this->redisRepository->save($synopsis);
            $success = $this->dbRepository->save($synopsis);

            return $success;
        }
    }