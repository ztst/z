<?php
    namespace Znaika\ProfileBundle\Repository;

    use Znaika\ProfileBundle\Entity\Region;

    interface IRegionRepository
    {
        /**
         * @param Region $user
         *
         * @return bool
         */
        public function save(Region $user);

        /**
         * @param Region $user
         *
         * @return bool
         */
        public function delete(Region $user);

        /**
         * @return Region[]
         */
        public function getAll();
    }