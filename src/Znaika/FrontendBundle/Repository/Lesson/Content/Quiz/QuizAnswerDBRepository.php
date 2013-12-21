<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer;

    class QuizAnswerDBRepository extends EntityRepository implements IQuizAnswerRepository
    {
        /**
         * @param QuizAnswer $quizAnswer
         *
         * @return mixed
         */
        public function save(QuizAnswer $quizAnswer)
        {
            $this->_em->persist($quizAnswer);
            $this->_em->flush();
        }
    }