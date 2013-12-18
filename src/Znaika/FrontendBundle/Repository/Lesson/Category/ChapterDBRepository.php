<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Doctrine\ORM\EntityRepository;

    class ChapterDBRepository extends EntityRepository implements IChapterRepository
    {
        /**
         * @return array|null
         */
        public function getAll()
        {
            return $this->findAll();
        }
    }
