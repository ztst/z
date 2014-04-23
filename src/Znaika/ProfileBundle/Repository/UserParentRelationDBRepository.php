<?
    namespace Znaika\ProfileBundle\Repository;

    use Doctrine\ORM\EntityRepository;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Entity\UserParentRelation;

    class UserParentRelationDBRepository extends EntityRepository implements IUserParentRelationRepository
    {
        public function save(UserParentRelation $userParentRelation)
        {
            $this->getEntityManager()->persist($userParentRelation);
            $this->getEntityManager()->flush();
        }

        public function delete(UserParentRelation $userParentRelation)
        {
            $this->getEntityManager()->remove($userParentRelation);
            $this->getEntityManager()->flush();
        }

        public function getUserParentRelation(User $child, User $parent)
        {
            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->select('upr')
               ->from('ZnaikaProfileBundle:UserParentRelation', 'upr')
               ->andWhere("upr.parent = :parent_id")
               ->setParameter('parent_id', $parent->getUserId())
               ->andWhere("upr.child = :child_id")
               ->setParameter('child_id', $child->getUserId())
               ->setMaxResults(1);

            return $qb->getQuery()->getOneOrNullResult();
        }
    }