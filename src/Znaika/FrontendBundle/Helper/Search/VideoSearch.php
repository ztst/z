<?
    namespace Znaika\FrontendBundle\Helper\Search;

    use Znaika\FrontendBundle\Helper\SphinxClient;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;

    class VideoSearch
    {
        const SPHINX_HOST       = "localhost";
        const SPHINX_PORT       = 9312;
        const SPHINX_TIMEOUT    = 1;
        const TOTAL_FOUND_FIELD = "total_found";
        const MATCHES_FIELD     = "matches";
        const ID_FIELD          = "id";
        const GRADE_FIELD       = "grade";
        const SUBJECT_ID_FIELD  = "subject_id";
        /**
         * @var VideoRepository
         */
        private $videoRepository;

        private $error;

        private $videoIds;

        private $totalFound;

        private $videos;

        public function __construct(VideoRepository $videoRepository)
        {
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

            $this->videos = $this->videoRepository->getByVideoIds($this->videoIds);

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
            $sphinx = new SphinxClient();
            $this->prepareSphinxParams($sphinx);

            $this->prepareSphinxLimit($limit, $page, $sphinx);
            $this->prepareSphinxFilters($subject, $grade, $sphinx);
            $this->prepareSphinxWeights($sphinx);

            $result = $this->processSearchResult($searchString, $sphinx);

            return $result !== false;
        }

        /**
         * @param $subject
         * @param $grade
         * @param $sphinx
         */
        private function prepareSphinxFilters($subject, $grade, SphinxClient $sphinx)
        {
            if ($grade)
            {
                $sphinx->SetFilter(self::GRADE_FIELD, array(intval($grade)));
            }
            if ($subject)
            {
                $sphinx->SetFilter(self::SUBJECT_ID_FIELD, array(intval($subject)));
            }
        }

        /**
         * @param $limit
         * @param $page
         * @param $sphinx
         */
        private function prepareSphinxLimit($limit, $page, SphinxClient $sphinx)
        {
            if ($limit)
            {
                $page = $page ? $page : 0;
                $sphinx->SetLimits($page * $limit, $limit);
            }
        }

        /**
         * @param $sphinx
         */
        private function prepareSphinxWeights(SphinxClient $sphinx)
        {
            $sphinx->SetFieldWeights(
                   array(
                       "video_name"    => 100,
                       "chapter_name"  => 50,
                       "synopsis_text" => 25,
                       "subject_name"  => 10
                   )
            );
        }

        /**
         * @param $sphinx
         */
        private function prepareSphinxParams(SphinxClient $sphinx)
        {
            $sphinx->SetServer(self::SPHINX_HOST, self::SPHINX_PORT);
            $sphinx->SetConnectTimeout(self::SPHINX_TIMEOUT);
            $sphinx->SetArrayResult(true);
            $sphinx->SetMatchMode(SPH_MATCH_ALL);
            $sphinx->SetSortMode(SPH_SORT_RELEVANCE);
        }

        /**
         * @param $searchString
         * @param $sphinx
         *
         * @return mixed
         */
        private function processSearchResult($searchString, SphinxClient $sphinx)
        {
            $result = $sphinx->Query($searchString, "*");

            $videoIds = array();
            if ($result === false)
            {
                $this->error = $sphinx->GetLastError();
            }
            else
            {
                $this->totalFound = $result[self::TOTAL_FOUND_FIELD];
                if (isset($result[self::MATCHES_FIELD]) && sizeof($result[self::MATCHES_FIELD]))
                {
                    foreach ($result[self::MATCHES_FIELD] as $match)
                    {
                        array_push($videoIds, $match[self::ID_FIELD]);
                    }
                }
            }

            $this->videoIds = $videoIds;

            return $result;
        }
    }