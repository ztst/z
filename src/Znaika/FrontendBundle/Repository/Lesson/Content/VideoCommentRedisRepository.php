<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;

    class VideoCommentRedisRepository implements IVideoCommentRepository
    {
        /**
         * @param VideoComment $videoComment
         *
         * @return mixed
         */
        public function save(VideoComment $videoComment)
        {
            return true;
        }

        /**
         * @param Video $video
         * @param $limit
         *
         * @return VideoComment[]
         */
        public function getLastVideoComments(Video $video, $limit)
        {
            return null;
        }

        /**
         * @param Video $video
         * @param $offset
         * @param $limit
         *
         * @return VideoComment[]
         */
        public function getVideoComments($video, $offset, $limit)
        {
            return null;
        }
    }