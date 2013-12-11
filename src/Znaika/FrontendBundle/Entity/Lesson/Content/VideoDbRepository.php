<?php

    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    use Doctrine\ORM\EntityRepository;

    class VideoDBRepository extends EntityRepository implements IVideoRepository
    {
        /**
         * @param null $classNumber
         * @param null $subjectName
         *
         * @return array|null
         */
        public function getVideosForCatalog($classNumber = null, $subjectName = null)
        {
            $videos = $this->getEntityManager()
                           ->createQueryBuilder()
                           ->select('v')
                           ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                           ->innerJoin('v.subject', 's')
                           ->where('s.urlName = :subjectName OR :subjectName IS NULL')
                           ->andWhere('v.grade = :classNumber OR :classNumber IS NULL')
                           ->addOrderBy('v.createdTime', 'DESC')
                //->setMaxResults(3) TODO: set limit
                           ->setParameter('subjectName', $subjectName)
                           ->setParameter('classNumber', $classNumber)
                           ->getQuery()
                           ->getResult();

            return $videos;
        }

        /**
         * @param $name
         *
         * @return Video|null
         */
        public function getOneByUrlName($name)
        {
            return $this->findOneByUrlName($name);
        }
    }
