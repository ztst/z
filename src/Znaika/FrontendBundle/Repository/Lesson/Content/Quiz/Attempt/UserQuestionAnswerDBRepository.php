<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz\Attempt;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer;

    class UserQuestionAnswerDBRepository extends EntityRepository implements IUserQuestionAnswerRepository
    {
        /**
         * @param UserQuestionAnswer $userQuestionAnswer
         *
         * @return mixed
         */
        public function save(UserQuestionAnswer $userQuestionAnswer)
        {
            $this->getEntityManager()->persist($userQuestionAnswer);
            $this->getEntityManager()->flush();
        }
    }