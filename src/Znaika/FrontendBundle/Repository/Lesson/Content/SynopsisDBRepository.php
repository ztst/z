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
            $this->getEntityManager()->persist($synopsis);
            $this->getEntityManager()->flush();
        }

        /**
         * @param string $searchString
         *
         * @return array|null
         */
        public function getSynopsisesBySearchString($searchString)
        {
            return array();
        }
    }