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
         * @param User $user
         *
         * @return UserParentRelation[]
         */
        public function getUserParentRelations(User $user);

        /**
         * @param User $user
         *
         * @return UserParentRelation[]
         */
        public function getUserChildRelations(User $user);
    }