<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;

    class ChapterDBRepository extends EntityRepository implements IChapterRepository
    {
        public function getAll()
        {
            return $this->findAll();
        }

        public function getOneById($chapterId)
        {
            return $this->findOneByChapterId($chapterId);
        }

        public function getChaptersForCatalog($grade, $subjectId)
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('ch')
                         ->from('ZnaikaFrontendBundle:Lesson\Category\Chapter', 'ch')
                         ->andWhere('ch.grade = :grade')
                         ->setParameter('grade', $grade)
                         ->andWhere('ch.subject = :subject_id')
                         ->setParameter('subject_id', $subjectId);

            $subjects = $queryBuilder->getQuery()->getResult();

            return $subjects;
        }
    }
