<?php
    namespace Znaika\ProfileBundle\Repository;

    use Znaika\ProfileBundle\Entity\Region;

    class RegionRedisRepository implements IRegionRepository
    {
        /**
         * @param Region $region
         *
         * @return bool
         */
        public function save(Region $region)
        {
            return true;
        }

        /**
         * @param Region $region
         *
         * @return bool
         */
        public function delete(Region $region)
        {
            return true;
        }

        /**
         * @return Region[]
         */
        public function getAll()
        {
            return null;
        }

        /**
         * @param string $name
         *
         * @return Region
         */
        public function getOneByName($name)
        {
            return null;
        }
    }