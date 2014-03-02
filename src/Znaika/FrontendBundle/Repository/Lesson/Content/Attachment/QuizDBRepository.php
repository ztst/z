<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Attachment;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;

    class QuizDBRepository extends EntityRepository implements IQuizRepository
    {
        /**
         * @param Quiz $quiz
         *
         * @return boolean
         */
        public function save(Quiz $quiz)
        {
            $this->getEntityManager()->persist($quiz);
            $this->getEntityManager()->flush();
        }

        /**
         * @param $videoId
         *
         * @return Quiz
         */
        public function getOneByVideoId($videoId)
        {
            return $this->findOneByVideo($videoId);
        }
    }