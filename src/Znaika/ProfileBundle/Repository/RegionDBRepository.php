<?php
    namespace Znaika\ProfileBundle\Repository;

    use Doctrine\ORM\EntityRepository;
    use Znaika\ProfileBundle\Entity\Region;

    class RegionDBRepository extends EntityRepository implements IRegionRepository
    {
        /**
         * @param Region $region
         *
         * @return bool
         */
        public function save(Region $region)
        {
            $this->getEntityManager()->persist($region);
            $this->getEntityManager()->flush();
        }

        /**
         * @param Region $region
         *
         * @return bool
         */
        public function delete(Region $region)
        {
            $this->getEntityManager()->remove($region);
            $this->getEntityManager()->flush();
        }

        /**
         * @return Region[]
         */
        public function getAll()
        {
            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->select('r')
                ->from('ZnaikaProfileBundle:Region', 'r');

            return $qb->getQuery()->getResult();
        }

        /**
         * @param string $name
         *
         * @return Region
         */
        public function getOneByName($name)
        {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('r')
                ->from('ZnaikaProfileBundle:Region', 'r')
                ->andWhere('r.regionName = :name')
                ->setParameter('name', $name);

            return $qb->getQuery()->getSingleResult();
        }
    }