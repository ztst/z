<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\User;

    interface IVideoCommentRepository
    {
        /**
         * @param VideoComment $videoComment
         *
         * @return mixed
         */
        public function save(VideoComment $videoComment);

        /**
         * @param $videoCommentId
         *
         * @return VideoComment
         */
        public function getOneByVideoCommentId($videoCommentId);

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

        /**
         * @param $video
         *
         * @return VideoComment[]
         */
        public function getVideoNotAnsweredQuestionComments(Video $video);

        /**
         * @param $user
         *
         * @return VideoComment[]
         */
        public function getTeacherNotAnsweredQuestionComments(User $user);

        /**
         * @param $user
         *
         * @return int
         */
        public function countTeacherNotAnsweredQuestionComments(User $user);
    }
