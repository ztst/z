<?
    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\ResponseHeaderBag;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\VideoAttachment;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Form\Lesson\Content\Attachment\VideoAttachmentType;
    use Znaika\FrontendBundle\Form\Lesson\Content\SynopsisType;
    use Znaika\FrontendBundle\Form\Lesson\Content\VideoCommentType;
    use Znaika\FrontendBundle\Form\Lesson\Content\VideoType;
    use Znaika\FrontendBundle\Helper\Content\ContentThumbnailUpdater;
    use Znaika\FrontendBundle\Helper\Uploader\VideoAttachmentUploader;
    use Znaika\FrontendBundle\Helper\UserOperation\UserOperationListener;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Znaika\FrontendBundle\Helper\Util\SocialNetworkUtil;
    use Znaika\FrontendBundle\Repository\Lesson\Category\ChapterRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\Attachment\IVideoAttachmentRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;

    class VideoController extends Controller
    {
        public function postVideoToSocialNetworkAction(Request $request)
        {
            $repository       = $this->getVideoRepository();
            $video            = $repository->getOneByUrlName($request->get('videoName'));
            $user             = $this->getUser();
            $network          = $request->get('network');
            $canSaveOperation = SocialNetworkUtil::isValidSocialNetwork($network) && !is_null($user) && !is_null($video);
            if ($canSaveOperation)
            {
                $listener = $this->getUserOperationListener();
                $listener->onPostVideoToSocialNetwork($user, $video, $network);
            }

            return new JsonResponse(array('success' => true));
        }

        public function downloadVideoAttachmentAction($attachmentId)
        {
            $repository = $this->getVideoAttachmentRepository();
            $attachment = $repository->getOneByVideoAttachmentId($attachmentId);

            /** @var VideoAttachmentUploader $uploader */
            $uploader = $this->get('znaika_frontend.video_attachment_uploader');
            $file     = $uploader->getAbsoluteFilePath($attachment);

            $response = new BinaryFileResponse($file);
            $response->headers->set('Content-Type', mime_content_type($file));
            $response->headers->set('Content-length', filesize($file));
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $attachment->getName());

            return $response;
        }

        /**
         * @return IVideoAttachmentRepository
         */
        private function getVideoAttachmentRepository()
        {
            return $this->get('znaika_frontend.video_attachment_repository');
        }

        public function addVideoAttachmentFormAction(Request $request)
        {
            $repository = $this->getVideoRepository();
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $videoAttachment = new VideoAttachment();
            $form            = $this->createForm(new VideoAttachmentType(), $videoAttachment);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $video->addVideoAttachment($videoAttachment);

                /** @var VideoAttachmentUploader $uploader */
                $uploader = $this->get('znaika_frontend.video_attachment_uploader');
                $uploader->upload($videoAttachment);

                $repository->save($video);

                return new RedirectResponse($this->generateUrl('show_video', array(
                    "class"       => $video->getGrade(),
                    "subjectName" => $video->getSubject()->getUrlName(),
                    "videoName"   => $video->getUrlName()
                )));
            }

            return $this->render('ZnaikaFrontendBundle:Video:addVideoAttachmentForm.html.twig', array(
                'form'  => $form->createView(),
                'video' => $video,
            ));
        }

        public function addSynopsisFormAction(Request $request)
        {
            $repository = $this->getVideoRepository();
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $synopsis = new Synopsis();
            $form     = $this->createForm(new SynopsisType(), $synopsis);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $video->setSynopsis($synopsis);

                $synopsisRepository = $this->get('znaika_frontend.synopsis_repository');
                $synopsisRepository->save($synopsis);

                return new RedirectResponse($this->generateUrl('show_video', array(
                    "class"       => $video->getGrade(),
                    "subjectName" => $video->getSubject()->getUrlName(),
                    "videoName"   => $video->getUrlName()
                )));
            }

            return $this->render('ZnaikaFrontendBundle:Video:addSynopsisForm.html.twig', array(
                'form'  => $form->createView(),
                'video' => $video,
            ));
        }

        public function addVideoCommentFormAction(Request $request)
        {
            $repository = $this->getVideoRepository();
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $videoComment = new VideoComment();
            $videoComment->setVideo($video);
            $videoComment->setUser($this->getUser());

            $form = $this->createForm(new VideoCommentType(), $videoComment);

            $form->handleRequest($request);
            if ($form->isValid())
            {
                $videoCommentRepository = $this->get('znaika_frontend.video_comment_repository');
                $videoCommentRepository->save($videoComment);

                $listener = $this->getUserOperationListener();
                $listener->onAddVideoComment($this->getUser(), $video);
            }

            return new RedirectResponse($this->generateUrl('show_video', array(
                "class"       => $video->getGrade(),
                "subjectName" => $video->getSubject()->getUrlName(),
                "videoName"   => $video->getUrlName()
            )));
        }

        public function addVideoFormAction(Request $request)
        {
            $video = new Video();
            $form  = $this->createForm(new VideoType(), $video);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $contentThumbnailUpdater = $this->getContentThumbnailUpdater();
                $contentThumbnailUpdater->update($video);

                $repository = $this->getVideoRepository();
                $repository->save($video);

                return new RedirectResponse($this->generateUrl('show_video', array(
                    "class"       => $video->getGrade(),
                    "subjectName" => $video->getSubject()->getUrlName(),
                    "videoName"   => $video->getUrlName()
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

            $repository = $this->getVideoRepository();
            $videos     = $repository->getVideosForCatalog($class, $subjectName);

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
            $subject           = $this->getSubjectByName($subjectName);
            $chapterRepository = $this->getChapterRepository();
            $chapters          = $chapterRepository->getChaptersForCatalog($class, $subject->getSubjectId());

            $currentChapterId = null;
            if (!empty($chapters) && isset($chapters[0]))
            {
                $currentChapterId = $chapters[0]->getChapterId();
            }

            return $this->render('ZnaikaFrontendBundle:Video:showCatalogue.html.twig', array(
                'currentChapterId' => $currentChapterId,
                'chapters'         => $chapters,
            ));
        }

        public function showVideoAction($class, $subjectName, $videoName)
        {
            $repository = $this->getVideoRepository();
            $video      = $repository->getOneByUrlName($videoName);

            $isValidUrl = false;
            if ($video)
            {
                $subject    = $video->getSubject();
                $isValidUrl = !is_null($video) && $subject->getUrlName() == $subjectName && $video->getGrade() == $class;
            }

            $videoComment        = new VideoComment();
            $addVideoCommentForm = $this->createForm(new VideoCommentType(), $videoComment);

            $user               = $this->getUser();
            $listener           = $this->getUserOperationListener();
            $viewVideoOperation = ($user) ? $listener->onViewVideo($user, $video) : null;

            $chapterVideos = $repository->getVideoByChapter($video->getChapter()->getChapterId());

            return $this->render('ZnaikaFrontendBundle:Video:showVideo.html.twig', array(
                'video'               => $video,
                'isValidUrl'          => $isValidUrl,
                'addVideoCommentForm' => $addVideoCommentForm->createView(),
                'viewVideoOperation'  => $viewVideoOperation,
                'chapterVideos'       => $chapterVideos,
            ));
        }

        /**
         * @return UserOperationListener
         */
        protected function getUserOperationListener()
        {
            return $this->get('znaika_frontend.user_operation_listener');
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
         * @return VideoRepository
         */
        private function getVideoRepository()
        {
            return $this->get("znaika_frontend.video_repository");
        }

        /**
         * @return ContentThumbnailUpdater
         */
        private function getContentThumbnailUpdater()
        {
            return $this->get("znaika_frontend.content_thumbnail_updater");
        }

        /**
         * @return ChapterRepository
         */
        private function getChapterRepository()
        {
            return $this->get("znaika_frontend.chapter_repository");
        }
    }
