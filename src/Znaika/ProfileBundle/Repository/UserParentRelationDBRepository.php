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

        public function getUserParentRelations(User $user)
        {
            return $this->findByParent($user);
        }

        public function getUserChildRelations(User $user)
        {
            return $this->findByChild($user);
        }
    }