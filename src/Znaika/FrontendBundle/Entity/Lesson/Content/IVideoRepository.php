<?php

    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    interface IVideoRepository
    {
        /**
         * @param null $classNumber
         * @param null $subjectName
         *
         * @return array|null
         */
        public function getVideosForCatalog($classNumber = null, $subjectName = null);

        /**
         * @param $name
         *
         * @return Video|null
         */
        public function getOneByUrlName($name);

        /**
         * @param Video $video
         *
         * @return bool
         */
        public function save(Video $video);
    }
