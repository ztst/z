<?
    namespace Znaika\FrontendBundle\Repository\Profile\Badge;

    use Znaika\FrontendBundle\Entity\Profile\Badge\BaseUserBadge;
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
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Profile\Badge\BaseUserBadge');

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

    }