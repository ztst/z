<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;

    class VideoCommentDBRepository extends EntityRepository implements IVideoCommentRepository
    {
        /**
         * @param VideoComment $videoComment
         *
         * @return mixed
         */
        public function save(VideoComment $videoComment)
        {
            $this->_em->persist($videoComment);
            $this->_em->flush();
        }
    }