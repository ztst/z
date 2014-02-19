<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserStatus;

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
         * @param $vkId
         *
         * @return User
         */
        public function getOneByVkId($vkId)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('u')
               ->from('ZnaikaFrontendBundle:Profile\User', 'u')
               ->andWhere("u.vkId = :vk_id")
               ->setParameter('vk_id', $vkId)
               ->andWhere('u.status = :status')
               ->setParameter('status', UserStatus::ACTIVE)
               ->setMaxResults(1);

            return $qb->getQuery()->getOneOrNullResult();
        }

        /**
         * @param $facebookId
         *
         * @return User
         */
        public function getOneByFacebookId($facebookId)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('u')
               ->from('ZnaikaFrontendBundle:Profile\User', 'u')
               ->andWhere("u.facebookId = :facebook_id")
               ->setParameter('facebook_id', $facebookId)
               ->andWhere('u.status = :status')
               ->setParameter('status', UserStatus::ACTIVE)
               ->setMaxResults(1);

            return $qb->getQuery()->getOneOrNullResult();
        }

        public function getOneByOdnoklassnikiId($odnoklassnikiId)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('u')
               ->from('ZnaikaFrontendBundle:Profile\User', 'u')
               ->andWhere("u.odnoklassnikiId = :odnoklassniki_id")
               ->setParameter('odnoklassniki_id', $odnoklassnikiId)
               ->andWhere('u.status = :status')
               ->setParameter('status', UserStatus::ACTIVE)
               ->setMaxResults(1);

            return $qb->getQuery()->getOneOrNullResult();
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
                                $qb->expr()->concat('u.firstName',
                                    $qb->expr()->concat($qb->expr()->literal(' '), 'u.lastName')),
                                    $qb->expr()->literal($searchString)
                             ),
                                 $qb->expr()->like(
                                    $qb->expr()->concat('u.lastName',
                                        $qb->expr()->concat($qb->expr()->literal(' '), 'u.firstName')),
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

        /**
         * @param integer $limit
         *
         * @return User[]
         */
        public function getUsersTopByPoints($limit)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('u')
               ->from('ZnaikaFrontendBundle:Profile\User', 'u')
               ->orderBy('u.points', 'DESC')
               ->setMaxResults($limit);

            return $qb->getQuery()->getResult();
        }
    }