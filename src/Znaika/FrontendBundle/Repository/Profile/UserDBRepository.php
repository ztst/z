<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class UserDBRepository extends EntityRepository implements IUserRepository
    {
        /**
         * @param User $user
         *
         * @return bool
         */
        public function save(User $user)
        {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }

        /**
         * @param $userId
         *
         * @return User|null
         */
        public function getOneByUserId($userId)
        {
            return $this->findOneByUserId($userId);
        }

        /**
         * @param string $searchString
         *
         * @return User[]|null
         */
        public function getUsersBySearchString($searchString)
        {
            $searchString = "%{$searchString}%";

            $qb = $this->getEntityManager()
                                 ->createQueryBuilder();
            $qb->select('u')
               ->from('ZnaikaFrontendBundle:Profile\User', 'u')
               ->where($qb->expr()->andX(
                   $qb->expr()->orX(
                       $qb->expr()->like(
                            $qb->expr()->concat('u.firstName', $qb->expr()->concat($qb->expr()->literal(' '), 'u.lastName')),
                            $qb->expr()->literal($searchString)
                       ),
                       $qb->expr()->like(
                           $qb->expr()->concat('u.lastName', $qb->expr()->concat($qb->expr()->literal(' '), 'u.firstName')),
                           $qb->expr()->literal($searchString)
                       )
                   )
               ))
               ->addOrderBy('u.createdTime', 'DESC');

            $videos = $qb->getQuery()->getResult();

            return $videos;
        }

        /**
         * @param string $email
         *
         * @return User|null
         */
        public function getOneByEmail($email)
        {
            return $this->findOneByEmail($email);
        }
    }