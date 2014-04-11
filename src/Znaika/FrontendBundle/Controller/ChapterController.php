<?
    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;
    use Znaika\FrontendBundle\Repository\Lesson\Category\ChapterRepository;
    use Znaika\FrontendBundle\Helper\Util\TransliterateUtil;
    use Znaika\FrontendBundle\Repository\Lesson\Category\SubjectRepository;

    class ChapterController extends ZnaikaController
    {
        public function editChaptersAction(Request $request)
        {
            $items = $request->get("items");

            $chapterRepository = $this->getChapterRepository();
            $counter = 1;
            foreach($items as $item)
            {
                $chapter = $chapterRepository->getOneById($item['id']);
                if (!$chapter)
                {
                    $chapter = $this->createNewChapter();
                }

                $title = $item['title'];
                if ($chapter->getName() != $title || $chapter->getOrderPriority() != $counter)
                {
                    $chapter->setName($title);
                    $urlName = str_replace(" ", "-", TransliterateUtil::transliterateFromCyrillic($title));
                    $chapter->setUrlName($urlName);
                    $chapter->setOrderPriority($counter);

                    $chapterRepository->save($chapter);
                }
                ++$counter;
            }

            $data = array(
                "success" => true
            );

            return JsonResponse::create($data);
        }

        private function createNewChapter()
        {
            $request = $this->getRequest();
            $subject = $this->getSubjectRepository()->getOneByUrlName($request->get("subjectName"));

            $chapter = new Chapter();
            $chapter->setSubject($subject);
            $chapter->setGrade($request->get("class"));
            $chapter->setCreatedTime(new \DateTime());

            return $chapter;
        }

        /**
         * @return ChapterRepository
         */
        private function getChapterRepository()
        {
            return $this->get("znaika.chapter_repository");
        }

        /**
         * @return SubjectRepository
         */
        private function getSubjectRepository()
        {
            return $this->get("znaika.subject_repository");
        }
    }
