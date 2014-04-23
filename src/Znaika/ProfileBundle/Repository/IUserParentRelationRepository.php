<?
    namespace Znaika\ProfileBundle\Repository;

    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Entity\UserParentRelation;

    interface IUserParentRelationRepository
    {
        /**
         * @param UserParentRelation $userParentRelation
         *
         * @return bool
         */
        public function save(UserParentRelation $userParentRelation);

        /**
         * @param UserParentRelation $userParentRelation
         *
         * @return bool
         */
        public function delete(UserParentRelation $userParentRelation);

        /**
         * @param User $child
         * @param User $parent
         *
         * @return UserParentRelation
         */
        public function getUserParentRelation(User $child, User $parent);
    }