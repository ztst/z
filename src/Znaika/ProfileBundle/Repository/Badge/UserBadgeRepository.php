<?
    namespace Znaika\ProfileBundle\Repository\Badge;

    use Znaika\ProfileBundle\Entity\Badge\BaseUserBadge;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class UserBadgeRepository extends BaseRepository implements IUserBadgeRepository
    {
        /**
         * @var IUserBadgeRepository
         */
        protected $dbRepository;

        /**
         * @var IUserBadgeRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new UserBadgeRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaProfileBundle:Badge\BaseUserBadge');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param BaseUserBadge $badge
         *
         * @return bool
         */
        public function save(BaseUserBadge $badge)
        {
            $this->redisRepository->save($badge);
            $success = $this->dbRepository->save($badge);

            return $success;
        }

        /**
         * @param User $user
         *
         * @return BaseUserBadge[]
         */
        public function getUserNotViewedBadges(User $user)
        {
            $result = $this->redisRepository->getUserNotViewedBadges($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->getUserNotViewedBadges($user);
            }

            return $result;
        }

        /**
         * @param integer $limit
         *
         * @return BaseUserBadge[]
         */
        public function getNewestBadges($limit)
        {
            $result = $this->redisRepository->getNewestBadges($limit);
            if (is_null($result))
            {
                $result = $this->dbRepository->getNewestBadges($limit);
            }

            return $result;
        }
    }