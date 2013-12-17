<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    use Doctrine\ORM\EntityRepository;

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
    }