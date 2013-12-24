<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;

    class ChapterDBRepository extends EntityRepository implements IChapterRepository
    {
        /**
         * @return array|null
         */
        public function getAll()
        {
            return $this->findAll();
        }

        /**
         * @param $chapterId
         *
         * @return null|Chapter
         */
        public function getOneById($chapterId)
        {
            return $this->findOneByChapterId($chapterId);
        }
    }
