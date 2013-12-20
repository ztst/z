<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis;

    class SynopsisRedisRepository implements ISynopsisRepository
    {
        /**
         * @param Synopsis $synopsis
         *
         * @return mixed
         */
        public function save(Synopsis $synopsis)
        {
            return true;
        }

        /**
         * @param string $searchString
         *
         * @return array|null
         */
        public function getSynopsisesBySearchString($searchString)
        {
            return null;
        }
    }