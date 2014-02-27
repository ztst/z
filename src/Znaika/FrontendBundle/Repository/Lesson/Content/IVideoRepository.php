<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;
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
         * @param $limit
         * @param $page
         *
         * @return Video[]|null
         */
        public function getVideosBySearchString($searchString, $limit = null, $page = null);

        /**
         * @param string $searchString
         *
         * @return int
         */
        public function countVideosBySearchString($searchString);

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
         * @param int $chapter
         *
         * @return Video[]
         */
        public function getVideoByChapter($chapter);

        /**
         * @param Video $video
         *
         * @return bool
         */
        public function save(Video $video);

        /**
         * @param Video $video
         * @param string $direction
         *
         * @return bool
         */
        public function moveVideo(Video $video, $direction);

        /**
         * @param Chapter $chapter
         *
         * @return int
         */
        public function getMaxChapterOrderPriority(Chapter $chapter);
    }
