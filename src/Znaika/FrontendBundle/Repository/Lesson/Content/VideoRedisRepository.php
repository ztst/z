<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class VideoRedisRepository implements IVideoRepository
    {
        public function getVideosForCatalog($classNumber = null, $subjectName = null)
        {
            return null;
        }

        public function getVideosBySearchString($searchString, $subjectName, $grade, $limit = null, $page = null)
        {
            return null;
        }

        public function countVideosBySearchString($searchString, $subjectName, $grade)
        {
            return null;
        }

        public function getOneByUrlName($name)
        {
            return null;
        }

        public function save(Video $video)
        {
            return true;
        }

        public function getNewestVideo($limit)
        {
            return null;
        }

        public function getPopularVideo($limit)
        {
            return null;
        }

        public function getVideoByChapter($chapter)
        {
            return null;
        }

        public function moveVideo(Video $video, $direction)
        {
            return true;
        }

        public function getMaxChapterOrderPriority(Chapter $chapter)
        {
            return null;
        }

        public function getOneByContentDir($dir)
        {
            return null;
        }

        public function getSupervisorVideosWithQuestions(User $user)
        {
            return null;
        }
    }
