<?
    namespace Znaika\ProfileBundle\Repository;

    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Entity\UserParentRelation;
    use Znaika\ProfileBundle\Entity\UserRegistration;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class UserParentRelationRepository extends BaseRepository implements IUserParentRelationRepository
    {
        /**
         * @var IUserParentRelationRepository
         */
        protected $dbRepository;

        /**
         * @var IUserParentRelationRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new UserParentRelationRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaProfileBundle:UserParentRelation');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @inheritdoc
         */
        public function save(UserParentRelation $userParentRelation)
        {
            $this->redisRepository->save($userParentRelation);
            $result = $this->dbRepository->save($userParentRelation);

            return $result;
        }

        /**
         * @inheritdoc
         */
        public function delete(UserParentRelation $userParentRelation)
        {
            $this->redisRepository->delete($userParentRelation);
            $result = $this->dbRepository->delete($userParentRelation);

            return $result;
        }

        /**
         * @inheritdoc
         */
        public function getUserParentRelations(User $user)
        {
            $result = $this->redisRepository->getUserParentRelations($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->getUserParentRelations($user);
            }

            return $result;
        }

        /**
         * @inheritdoc
         */
        public function getUserChildRelations(User $user)
        {
            $result = $this->redisRepository->getUserChildRelations($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->getUserChildRelations($user);
            }

            return $result;
        }
    }