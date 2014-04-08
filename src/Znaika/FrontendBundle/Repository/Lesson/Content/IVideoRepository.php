<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\User;

    interface IVideoRepository
    {
        /**
         * @param null $classNumber
         * @param null $subjectName
         *
         * @return Video[]|null
         */
        public function getVideosForCatalog($classNumber = null, $subjectName = null);

        public function getByVideoIds($videoIds);

        /**
         * @param $name
         *
         * @return Video|null
         */
        public function getOneByUrlName($name);

        /**
         * @param $videoId
         *
         * @return Video
         */
        public function getOneByVideoId($videoId);

        /**
         * @param $dir
         *
         * @return Video
         */
        public function getOneByContentDir($dir);

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

        /**
         * @param User $user
         *
         * @return Video[]
         */
        public function getSupervisorVideosWithQuestions(User $user);

        /**
         * @return Video[]
         */
        public function getVideosWithNotVerifiedComments();
    }
