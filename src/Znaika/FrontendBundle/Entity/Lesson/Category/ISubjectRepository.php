<?php

    namespace Znaika\FrontendBundle\Entity\Lesson\Category;

    interface ISubjectRepository
    {
        /**
         * @return array|null
         */
        public function getAll();

        /**
         * @param $name
         *
         * @return Subject|null
         */
        public function getOneByUrlName($name);
    }
