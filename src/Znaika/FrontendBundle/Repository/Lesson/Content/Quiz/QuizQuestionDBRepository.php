<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion;

    class QuizQuestionDBRepository extends EntityRepository implements IQuizQuestionRepository
    {
        /**
         * @param QuizQuestion $quizQuestion
         *
         * @return mixed
         */
        public function save(QuizQuestion $quizQuestion)
        {
            $this->_em->persist($quizQuestion);
            $this->_em->flush();
        }
    }