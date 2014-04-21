<?
    namespace Znaika\ProfileBundle\Repository;

    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Entity\UserParentRelation;

    class UserParentRelationRedisRepository implements IUserParentRelationRepository
    {
        public function save(UserParentRelation $userParentRelation)
        {
            return null;
        }

        public function delete(UserParentRelation $userParentRelation)
        {
            return null;
        }

        public function getUserParentRelations(User $user)
        {
            return null;
        }

        public function getUserChildRelations(User $user)
        {
            return null;
        }
    }