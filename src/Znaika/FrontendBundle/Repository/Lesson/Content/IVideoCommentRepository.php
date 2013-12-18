<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;

    interface IVideoCommentRepository
    {
        /**
         * @param VideoComment $videoComment
         *
         * @return mixed
         */
        public function save(VideoComment $videoComment);
    }
