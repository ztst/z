<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoView;

    class VideoViewDBRepository extends EntityRepository implements IVideoViewRepository
    {
        /**
         * @param VideoView $videoView
         *
         * @return mixed
         */
        public function save(VideoView $videoView)
        {
            $this->_em->persist($videoView);
            $this->_em->flush();
        }

        /**
         * @param $video
         * @param $user
         *
         * @return VideoView
         */
        public function getOneByVideoAndUser($video, $user)
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();

            $queryBuilder->select('v')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\VideoView', 'v');

            $queryBuilder->andWhere('v.video = :video')
                         ->setParameter('video', $video);

            $queryBuilder->andWhere('v.user = :user')
                         ->setParameter('user', $user);

            $videoView = $queryBuilder->getQuery()->getOneOrNullResult();

            return $videoView;
        }
    }