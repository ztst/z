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
         * @param $videoCommentId
         *
         * @return VideoComment
         */
        public function getOneByVideoCommentId($videoCommentId)
        {
            return null;
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

        /**
         * @param $video
         *
         * @return VideoComment[]
         */
        public function getVideoNotAnsweredQuestionComments($video)
        {
            return null;
        }
    }