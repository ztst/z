<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Stat;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Stat\QuizAttempt;

    class QuizAttemptDBRepository extends EntityRepository implements IQuizAttemptRepository
    {
        public function save(QuizAttempt $quizAttempt)
        {
            $this->getEntityManager()->persist($quizAttempt);
            $this->getEntityManager()->flush();
        }

        public function getUserQuizAttempt($userId, $quizId)
        {            $queryBuilder = $this->getEntityManager()
                                          ->createQueryBuilder();
            $queryBuilder->select('qa')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Stat\QuizAttempt', 'qa')
                         ->andWhere('qa.user = :user_id')
                         ->setParameter('user_id', $userId)
                         ->andWhere('qa.quiz = :quiz_id')
                         ->setParameter('quiz_id', $quizId);

            return $queryBuilder->getQuery()->getOneOrNullResult();
        }
    }