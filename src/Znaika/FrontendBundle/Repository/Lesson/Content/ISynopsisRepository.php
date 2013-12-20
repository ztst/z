<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis;

    interface ISynopsisRepository
    {
        /**
         * @param Synopsis $synopsis
         *
         * @return mixed
         */
        public function save(Synopsis $synopsis);

        /**
         * @param string $searchString
         *
         * @return array|null
         */
        public function getSynopsisesBySearchString($searchString);
    }
