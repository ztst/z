<?
    namespace Znaika\FrontendBundle\Helper\Search;

    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;

    class VideoSearch extends SphinxSearch
    {
        const GRADE_FIELD      = "grade";
        const SUBJECT_ID_FIELD = "subject_id";
        const VIDEO_INDEX_NAME = "znaika_video";
        /**
         * @var VideoRepository
         */
        private $videoRepository;

        private $videos;

        public function __construct(VideoRepository $videoRepository)
        {
            parent::__construct();

            $this->videoRepository = $videoRepository;
        }

        public function getVideosBySearchString($searchString, $subjectName, $grade, $limit = null, $page = null)
        {
            if (!$this->videos)
            {
                if (!$this->prepareSearchingVideoResult($searchString, $subjectName, $grade, $limit, $page))
                {
                    throw new \Exception("Sphinx Error: " . $this->error);
                }
            }

            $this->videos = $this->videoRepository->getByVideoIds($this->ids);

            return $this->videos;
        }

        public function countVideosBySearchString($searchString, $subjectName, $grade)
        {
            if (!$this->totalFound)
            {
                if (!$this->prepareSearchingVideoResult($searchString, $subjectName, $grade))
                {
                    throw new \Exception("Sphinx Error: " . $this->error);
                }
            }

            return $this->totalFound;
        }

        protected function getIndexName()
        {
            return self::VIDEO_INDEX_NAME;
        }

        /**
         * @param $searchString
         * @param $subject
         * @param $grade
         * @param $limit
         * @param $page
         *
         * @return bool
         * @throws \Exception
         */
        private function prepareSearchingVideoResult($searchString, $subject, $grade, $limit = null, $page = null)
        {
            $this->prepareSphinxLimit($limit, $page);
            $this->prepareSphinxFilters($subject, $grade);
            $this->prepareSphinxWeights();

            $result = $this->processSearchResult($searchString);

            return $result !== false;
        }

        /**
         * @param $subject
         * @param $grade
         */
        private function prepareSphinxFilters($subject, $grade)
        {
            if ($grade)
            {
                $this->sphinx->SetFilter(self::GRADE_FIELD, array(intval($grade)));
            }
            if ($subject)
            {
                $this->sphinx->SetFilter(self::SUBJECT_ID_FIELD, array(intval($subject)));
            }
        }

        /**
         * @param $limit
         * @param $page
         */
        private function prepareSphinxLimit($limit, $page)
        {
            if ($limit)
            {
                $page = $page ? $page : 0;
                $this->sphinx->SetLimits($page * $limit, $limit);
            }
        }

        private function prepareSphinxWeights()
        {
            $this->sphinx->SetFieldWeights(
                array(
                    "video_name"    => 100,
                    "chapter_name"  => 50,
                    "synopsis_text" => 25,
                    "subject_name"  => 10,
                )
            );
        }
    }