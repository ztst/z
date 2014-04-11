<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\ProfileBundle\Entity\User;

    class VideoCommentRedisRepository implements IVideoCommentRepository
    {
        public function save(VideoComment $videoComment)
        {
            return true;
        }

        public function getOneByVideoCommentId($videoCommentId)
        {
            return null;
        }

        public function getByVideoCommentIds($videoCommentIds)
        {
            return null;
        }

        public function getLastVideoComments(Video $video, $limit)
        {
            return null;
        }

        public function getVideoComments($video, $offset, $limit)
        {
            return null;
        }

        public function countVideoComments(Video $video)
        {
            return null;
        }

        public function getVideoNotAnsweredQuestionComments(Video $video)
        {
            return null;
        }

        public function getTeacherNotAnsweredQuestionComments(User $user)
        {
            return null;
        }

        public function countTeacherNotAnsweredQuestionComments(User $user)
        {
            return null;
        }

        /**
         * @internal param $user
         *
         * @return VideoComment[]
         */
        public function getModeratorNotVerifiedComments()
        {
            return null;
        }

        /**
         * @internal param $user
         *
         * @return int
         */
        public function countModeratorNotVerifiedComments()
        {
            return null;
        }

        public function getVideoNotVerifiedComments(Video $video)
        {
            return null;
        }
    }