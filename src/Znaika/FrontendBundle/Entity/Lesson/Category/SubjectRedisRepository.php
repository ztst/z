<?php

    namespace Znaika\FrontendBundle\Entity\Lesson\Category;

    class SubjectRedisRepository implements ISubjectRepository
    {
        /**
         * @return array|null
         */
        public function getAll()
        {
            return null;
        }

        /**
         * @param $name
         *
         * @return Subject|null
         */
        public function getOneByUrlName($name)
        {
            return null;
        }
    }
