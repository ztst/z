<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;

    interface IVideoRepository
    {
        /**
         * @param null $classNumber
         * @param null $subjectName
         *
         * @return array|null
         */
        public function getVideosForCatalog($classNumber = null, $subjectName = null);

        /**
         * @param string $searchString
         *
         * @return array|null
         */
        public function getVideosBySearchString($searchString);

        /**
         * @param $name
         *
         * @return Video|null
         */
        public function getOneByUrlName($name);

        /**
         * @param Video $video
         *
         * @return bool
         */
        public function save(Video $video);
    }
