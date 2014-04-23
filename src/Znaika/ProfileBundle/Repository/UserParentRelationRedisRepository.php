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

        public function getUserParentRelation(User $child, User $parent)
        {
            return null;
        }
    }