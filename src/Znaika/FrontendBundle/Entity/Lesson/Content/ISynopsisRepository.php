<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    interface ISynopsisRepository
    {
        /**
         * @param Synopsis $synopsis
         *
         * @return mixed
         */
        public function save(Synopsis $synopsis);
    }
