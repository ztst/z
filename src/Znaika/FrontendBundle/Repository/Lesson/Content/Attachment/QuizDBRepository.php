<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Attachment;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;

    class QuizDBRepository extends EntityRepository implements IQuizRepository
    {
        public function save(Quiz $quiz)
        {
            $this->getEntityManager()->persist($quiz);
            $this->getEntityManager()->flush();
        }

        public function getOneByVideoId($videoId)
        {
            return $this->findOneByVideo($videoId);
        }

        public function getOneByName($name)
        {
            return $this->findOneByName($name);
        }
    }