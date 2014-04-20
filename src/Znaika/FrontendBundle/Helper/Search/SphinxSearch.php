<?
    namespace Znaika\FrontendBundle\Helper\Search;

    use Znaika\FrontendBundle\Helper\SphinxClient;

    abstract class SphinxSearch
    {
        const SPHINX_HOST    = "localhost";
        const SPHINX_PORT    = 9312;
        const SPHINX_TIMEOUT = 1;

        const TOTAL_FOUND_FIELD = "total_found";
        const MATCHES_FIELD     = "matches";
        const ID_FIELD          = "id";

        /**
         * @var SphinxClient
         */
        protected $sphinx;

        /**
         * @var string
         */
        protected $error;

        /**
         * @var int
         */
        protected $totalFound;

        /**
         * @var array
         */
        protected $ids;

        function __construct()
        {
            $this->sphinx = new SphinxClient();
            $this->prepareSphinxParams();
        }

        abstract protected function getIndexName();

        protected function prepareSphinxParams()
        {
            $this->sphinx->SetServer(self::SPHINX_HOST, self::SPHINX_PORT);
            $this->sphinx->SetConnectTimeout(self::SPHINX_TIMEOUT);
            $this->sphinx->SetArrayResult(true);
            $this->sphinx->SetMatchMode(SPH_MATCH_ALL);
            $this->sphinx->SetSortMode(SPH_SORT_RELEVANCE);
        }

        protected function doSearch($searchString)
        {
            return $this->sphinx->Query($searchString, $this->getIndexName());
        }

        /**
         * @param $searchString
         *
         * @return mixed
         */
        protected  function processSearchResult($searchString)
        {
            $result = $this->doSearch($searchString);

            $ids = array();
            if ($result === false)
            {
                $this->error = $this->sphinx->GetLastError();
            }
            else
            {
                $this->totalFound = $result[self::TOTAL_FOUND_FIELD];
                if (isset($result[self::MATCHES_FIELD]) && sizeof($result[self::MATCHES_FIELD]))
                {
                    foreach ($result[self::MATCHES_FIELD] as $match)
                    {
                        array_push($ids, $match[self::ID_FIELD]);
                    }
                }
            }

            $this->ids = $ids;

            return $result;
        }
    }