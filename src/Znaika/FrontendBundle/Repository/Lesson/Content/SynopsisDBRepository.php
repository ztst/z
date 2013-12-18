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
    }