<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Entity\Profile\User;

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

        public function getLastVideoComments(Video $video, $limit)
        {
            return null;
        }

        public function getVideoComments($video, $offset, $limit)
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
    }