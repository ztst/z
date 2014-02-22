<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;

    interface IVideoCommentRepository
    {
        /**
         * @param VideoComment $videoComment
         *
         * @return mixed
         */
        public function save(VideoComment $videoComment);

        /**
         * @param Video $video
         * @param $limit
         *
         * @return VideoComment[]
         */
        public function getLastVideoComments(Video $video, $limit);

        /**
         * @param Video $video
         * @param $offset
         * @param $limit
         *
         * @return VideoComment[]
         */
        public function getVideoComments($video, $offset, $limit);
    }
