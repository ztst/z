<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    class SynopsisRedisRepository implements ISynopsisRepository
    {
        /**
         * @param Synopsis $synopsis
         *
         * @return mixed
         */
        public function save(Synopsis $synopsis)
        {
            return true;
        }
    }