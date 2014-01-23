<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;

    interface IVideoRepository
    {
        /**
         * @param null $classNumber
         * @param null $subjectName
         *
         * @return Video[]|null
         */
        public function getVideosForCatalog($classNumber = null, $subjectName = null);

        /**
         * @param string $searchString
         *
         * @return Video[]|null
         */
        public function getVideosBySearchString($searchString);

        /**
         * @param $name
         *
         * @return Video|null
         */
        public function getOneByUrlName($name);

        /**
         * @param $limit
         *
         * @return Video[]
         */
        public function getNewestVideo($limit);

        /**
         * @param $limit
         *
         * @return Video[]
         */
        public function getPopularVideo($limit);

        /**
         * @param Video $video
         *
         * @return bool
         */
        public function save(Video $video);
    }
