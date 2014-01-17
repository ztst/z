<?
    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoView;
    use Znaika\FrontendBundle\Form\Lesson\Content\SynopsisType;
    use Znaika\FrontendBundle\Form\Lesson\Content\VideoCommentType;
    use Znaika\FrontendBundle\Form\Lesson\Content\VideoType;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoViewRepository;

    class VideoController extends Controller
    {
        public function addSynopsisFormAction(Request $request)
        {
            $repository = $this->get("znaika_frontend.video_repository");
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $synopsis = new Synopsis();
            $form  = $this->createForm(new SynopsisType(), $synopsis);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $video->setSynopsis($synopsis);

                $synopsisRepository = $this->get('znaika_frontend.synopsis_repository');
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
            $repository = $this->get("znaika_frontend.video_repository");
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $videoComment = new VideoComment();
            $videoComment->setVideo($video);
            $videoComment->setUser($this->getUser());

            $form  = $this->createForm(new VideoCommentType(), $videoComment);

            $form->handleRequest($request);
            if ($form->isValid())
            {
                $videoCommentRepository = $this->get('znaika_frontend.video_comment_repository');
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
                $videoRepository = $this->get('znaika_frontend.video_repository');
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

            $videoRepository = $this->get("znaika_frontend.video_repository");
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
            $subjectRepository = $this->get("znaika_frontend.subject_repository");
            $subjects          = $subjectRepository->getAll();
            $classes           = ClassNumberUtil::getAvailableClasses();

            $videoRepository = $this->get("znaika_frontend.video_repository");
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
            $repository = $this->get("znaika_frontend.video_repository");
            $video      = $repository->getOneByUrlName($videoName);

            $isValidUrl = false;
            if ($video)
            {
                $subject = $video->getSubject();
                $isValidUrl = !is_null($video) && $subject->getUrlName() == $subjectName && $video->getGrade() == $class;

            }

            $videoComment = new VideoComment();
            $addVideoCommentForm  = $this->createForm(new VideoCommentType(), $videoComment);

            $videoView = $this->getVideoView($video);
            if (!$videoView)
            {
                $this->saveVideoView($video);
            }

            return $this->render('ZnaikaFrontendBundle:Video:showVideo.html.twig', array(
                'video'               => $video,
                'isValidUrl'          => $isValidUrl,
                'addVideoCommentForm' => $addVideoCommentForm->createView(),
                'videoView'           => $videoView
            ));
        }

        /**
         * @param $subjectName
         *
         * @return Subject
         */
        protected function getSubjectByName($subjectName)
        {
            $subject = null;
            if ($subjectName)
            {
                $repository = $this->get('znaika_frontend.subject_repository');
                $subject    = $repository->getOneByUrlName($subjectName);
            }

            return $subject;
        }

        /**
         * @param $video
         *
         * @return VideoView
         */
        private function getVideoView($video)
        {
            $repository = $this->getVideoViewRepository();

            return $repository->getOneByVideoAndUser($video, $this->getUser());
        }

        /**
         * @param $video
         */
        private function saveVideoView($video)
        {
            $repository = $this->getVideoViewRepository();

            $videoView = new VideoView();
            $videoView->setVideo($video);
            $videoView->setUser($this->getUser());

            $repository->save($videoView);
        }

        /**
         * @return VideoViewRepository
         */
        private function getVideoViewRepository()
        {
            return $this->get("znaika_frontend.video_view_repository");
        }
    }
