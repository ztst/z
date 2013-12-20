<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis;

    class SynopsisDBRepository extends EntityRepository implements ISynopsisRepository
    {
        /**
         * @param Synopsis $synopsis
         *
         * @return mixed
         */
        public function save(Synopsis $synopsis)
        {
            $this->_em->persist($synopsis);
            $this->_em->flush();
        }

        /**
         * @param string $searchString
         *
         * @return array|null
         */
        public function getSynopsisesBySearchString($searchString)
        {
            $searchString = "%{$searchString}%";

            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('s')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Synopsis', 's')
                         ->where($queryBuilder->expr()->like( 's.text', $queryBuilder->expr()->literal($searchString) ))
                         ->addOrderBy('s.createdTime', 'DESC');

            $synopsises = $queryBuilder->getQuery()->getResult();

            return $synopsises;
        }
    }