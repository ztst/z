<?
    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Form\Lesson\Content\SynopsisType;
    use Znaika\FrontendBundle\Form\Lesson\Content\VideoCommentType;
    use Znaika\FrontendBundle\Form\Lesson\Content\VideoType;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\JsonResponse;

    class VideoController extends Controller
    {
        public function addSynopsisFormAction(Request $request)
        {
            $repository = $this->get("video_repository");
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $synopsis = new Synopsis();
            $form  = $this->createForm(new SynopsisType(), $synopsis);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $synopsis->setVideo($video);
                $video->setSynopsis($synopsis);

                $synopsisRepository = $this->get('synopsis_repository');
                $synopsisRepository->save($synopsis);

                return new RedirectResponse($this->generateUrl('show_video', array(
                    "class" => $video->getGrade(),
                    "subjectName" => $video->getSubject()->getUrlName(),
                    "videoName" => $video->getUrlName()
                )));
            }

            return $this->render('ZnaikaFrontendBundle:Video:addSynopsisForm.html.twig', array(
                'form' => $form->createView(),
                'video'=> $video,
            ));
        }

        public function addVideoCommentFormAction(Request $request)
        {
            $repository = $this->get("video_repository");
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $videoComment = new VideoComment();
            $videoComment->setVideo($video);
            $videoComment->setUser($this->getUser());

            $form  = $this->createForm(new VideoCommentType(), $videoComment);

            $form->handleRequest($request);
            if ($form->isValid())
            {
                $videoCommentRepository = $this->get('video_comment_repository');
                $videoCommentRepository->save($videoComment);
            }

            return new RedirectResponse($this->generateUrl('show_video', array(
                "class" => $video->getGrade(),
                "subjectName" => $video->getSubject()->getUrlName(),
                "videoName" => $video->getUrlName()
            )));
        }

        public function addVideoFormAction(Request $request)
        {
            $video = new Video();
            $form  = $this->createForm(new VideoType(), $video);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $videoRepository = $this->get('video_repository');
                $videoRepository->save($video);

                return new RedirectResponse($this->generateUrl('show_video', array(
                    "class" => $video->getGrade(),
                    "subjectName" => $video->getSubject()->getUrlName(),
                    "videoName" => $video->getUrlName()
                )));
            }

            return $this->render('ZnaikaFrontendBundle:Video:addVideoForm.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        public function getVideosAction(Request $request)
        {
            $subjectName = $request->request->get("subject");
            $class       = $request->request->get("class");

            $videoRepository = $this->get("video_repository");
            $videos          = $videoRepository->getVideosForCatalog($class, $subjectName);

            $response = new JsonResponse();
            $content  = $this->renderView('ZnaikaFrontendBundle:Video:video_catalog.html.twig', array(
                'videos' => $videos
            ));

            $response->setData(array(
                'content' => $content
            ));

            return $response;
        }

        public function showCatalogueAction($class, $subjectName)
        {
            $subjectRepository = $this->get("subject_repository");
            $subjects          = $subjectRepository->getAll();
            $classes           = ClassNumberUtil::getAvailableClasses();

            $videoRepository = $this->get("video_repository");
            $videos          = $videoRepository->getVideosForCatalog($class, $subjectName);

            return $this->render('ZnaikaFrontendBundle:Video:showCatalogue.html.twig', array(
                'classes'            => $classes,
                'currentClass'       => $class,
                'currentSubjectName' => $subjectName,
                'subjects'           => $subjects,
                'videos'             => $videos,
            ));
        }

        public function showVideoAction($class, $subjectName, $videoName)
        {
            $repository = $this->get("video_repository");
            $video      = $repository->getOneByUrlName($videoName);

            $isValidUrl = false;
            if ($video)
            {
                $subject = $video->getSubject();
                $isValidUrl = !is_null($video) && $subject->getUrlName() == $subjectName && $video->getGrade() == $class;
            }

            $videoComment = new VideoComment();
            $addVideoCommentForm  = $this->createForm(new VideoCommentType(), $videoComment);

            return $this->render('ZnaikaFrontendBundle:Video:showVideo.html.twig', array(
                'video'               => $video,
                'isValidUrl'          => $isValidUrl,
                'addVideoCommentForm' => $addVideoCommentForm->createView(),
            ));
        }

        /**
         * @param $subjectName
         *
         * @return null
         */
        protected function getSubjectByName($subjectName)
        {
            $subject = null;
            if ($subjectName)
            {
                $repository = $this->get('subject_repository');
                $subject    = $repository->getOneByUrlName($subjectName);
            }

            return $subject;
        }
    }
