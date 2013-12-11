<?php

    namespace Znaika\FrontendBundle\Entity\Lesson\Category;

    use Doctrine\ORM\EntityRepository;

    class SubjectDBRepository extends EntityRepository implements ISubjectRepository
    {
        /**
         * @param $name
         *
         * @return Subject|null
         */
        public function getOneByUrlName($name)
        {
            return $this->findOneByUrlName($name);
        }

        /**
         * @return array|null
         */
        public function getAll()
        {
            return $this->findAll();
        }
    }
