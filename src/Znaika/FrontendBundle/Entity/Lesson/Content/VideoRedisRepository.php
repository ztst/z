<?php

    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    class VideoRedisRepository implements IVideoRepository
    {
        /**
         * @param null $classNumber
         * @param null $subjectName
         *
         * @return array|null
         */
        public function getVideosForCatalog($classNumber = null, $subjectName = null)
        {
            return null;
        }

        /**
         * @param $name
         *
         * @return Video|null
         */
        public function getOneByUrlName($name)
        {
            return null;
        }

        /**
         * @param Video $video
         *
         * @return bool
         */
        public function save(Video $video)
        {
            return true;
        }

    }
