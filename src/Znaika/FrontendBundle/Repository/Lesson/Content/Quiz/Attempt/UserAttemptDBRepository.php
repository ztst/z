<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz\Attempt;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt;

    class UserAttemptDBRepository extends EntityRepository implements IUserAttemptRepository
    {
        /**
         * @param UserAttempt $userAttempt
         *
         * @return mixed
         */
        public function save(UserAttempt $userAttempt)
        {
            $this->getEntityManager()->persist($userAttempt);
            $this->getEntityManager()->flush();
        }
    }