<?
    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\ResponseHeaderBag;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\VideoAttachment;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Stat\QuizAttempt;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Form\Lesson\Content\Attachment\VideoAttachmentType;
    use Znaika\FrontendBundle\Form\Lesson\Content\SynopsisType;
    use Znaika\FrontendBundle\Form\Lesson\Content\VideoCommentType;
    use Znaika\FrontendBundle\Form\Lesson\Content\VideoType;
    use Znaika\FrontendBundle\Helper\Content\ContentThumbnailUpdater;
    use Znaika\FrontendBundle\Helper\Uploader\SynopsisUploader;
    use Znaika\FrontendBundle\Helper\Uploader\VideoAttachmentUploader;
    use Znaika\FrontendBundle\Helper\UserOperation\UserOperationListener;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentUtil;
    use Znaika\FrontendBundle\Helper\Util\SocialNetworkUtil;
    use Znaika\FrontendBundle\Repository\Lesson\Category\ChapterRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\Attachment\IVideoAttachmentRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\Stat\QuizAttemptRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoCommentRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class VideoController extends Controller
    {
        const COMMENTS_LIMIT_ON_SHOW_VIDEO_PAGE = 3;

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

            $synopsis = $video->getSynopsis() ? $video->getSynopsis() : new Synopsis();
            $form     = $this->createForm(new SynopsisType(), $synopsis);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $video->setSynopsis($synopsis);

                /** @var SynopsisUploader $uploader */
                $uploader = $this->get('znaika_frontend.synopsis_uploader');
                $uploader->upload($synopsis);

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
            if (!$this->getUser())
            {
                throw $this->createNotFoundException("Not logged user try save comment");
            }

            $repository = $this->getVideoRepository();
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $videoComment = new VideoComment();
            $videoComment->setVideo($video);
            $videoComment->setUser($this->getUser());

            $form = $this->createForm(new VideoCommentType(), $videoComment);

            $form->handleRequest($request);
            if ($form->isValid())
            {
                $videoCommentRepository = $this->getVideoCommentRepository();

                $this->prepareVideoCommentType($videoComment, $videoCommentRepository);

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

        public function editVideoFormAction($videoName)
        {
            $repository = $this->getVideoRepository();
            $video      = $repository->getOneByUrlName($videoName);

            $form    = $this->createForm(new VideoType($this->getUserRepository()), $video);
            $request = $this->getRequest();
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $contentThumbnailUpdater = $this->getContentThumbnailUpdater();
                $contentThumbnailUpdater->update($video);

                $repository->save($video);

                return new RedirectResponse($this->generateUrl('show_video', array(
                    "class"       => $video->getGrade(),
                    "subjectName" => $video->getSubject()->getUrlName(),
                    "videoName"   => $video->getUrlName(),
                )));
            }

            return $this->render('ZnaikaFrontendBundle:Video:editVideoForm.html.twig', array(
                "form"      => $form->createView(),
                "videoName" => $videoName
            ));
        }

        public function addVideoFormAction(Request $request)
        {
            $grade       = $request->get("class");
            $subject     = $this->getSubjectByName($request->get("subjectName"));
            $chapterName = $request->get("chapterName");
            $chapter     = $this->getChapterRepository()->getOne($chapterName, $grade, $subject->getSubjectId());

            $video = new Video();
            $form  = $this->createForm(new VideoType($this->getUserRepository()), $video);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $contentThumbnailUpdater = $this->getContentThumbnailUpdater();
                $contentThumbnailUpdater->update($video);

                $video->setInfoFromChapter($chapter);
                $repository = $this->getVideoRepository();
                $repository->save($video);

                return new RedirectResponse($this->generateUrl('show_video', array(
                    "class"       => $video->getGrade(),
                    "subjectName" => $video->getSubject()->getUrlName(),
                    "videoName"   => $video->getUrlName(),
                )));
            }

            return $this->render('ZnaikaFrontendBundle:Video:addVideoForm.html.twig', array(
                "form"    => $form->createView(),
                "chapter" => $chapter,
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
                'videosAmount'     => $chapters[0]->GetVideos()->count(),
                'currentChapterId' => $currentChapterId,
                'chapters'         => $chapters,
                'videoRepository'  => $this->getVideoRepository()
            ));
        }

        public function moveVideoAjaxAction(Request $request)
        {
            $videoRepository = $this->getVideoRepository();
            $video           = $videoRepository->getOneByUrlName($request->get("videoName"));
            $direction       = $request->get("direction");

            $success = false;

            if ($video)
            {
                $success = $videoRepository->moveVideo($video, $direction);
            }

            return new JsonResponse(array("success" => $success));
        }

        public function showVideoAction($class, $subjectName, $videoName)
        {
            $repository = $this->getVideoRepository();
            $video      = $repository->getOneByUrlName($videoName);

            $isValidUrl = false;
            if (!$video)
            {
                throw $this->createNotFoundException("Video not found");
            }
            else
            {
                $subject    = $video->getSubject();
                $isValidUrl = $subject->getUrlName() == $subjectName && $video->getGrade() == $class;
            }

            $addVideoCommentForm = $this->getAddVideoCommentForm();
            $viewVideoOperation  = $this->saveViewVideoOperation($video);
            $chapterVideos       = $repository->getVideoByChapter($video->getChapter()->getChapterId());
            $comments            = $this->getLastVideoComments($video);
            $userQuizScore       = $this->getCurrentUserQuizScore($video);

            return $this->render('ZnaikaFrontendBundle:Video:showVideo.html.twig', array(
                'video'               => $video,
                'comments'            => $comments,
                'isValidUrl'          => $isValidUrl,
                'addVideoCommentForm' => $addVideoCommentForm->createView(),
                'viewVideoOperation'  => $viewVideoOperation,
                'chapterVideos'       => $chapterVideos,
                'userQuizScore'       => $userQuizScore,
            ));
        }

        public function getPrevCommentsAction(Request $request)
        {
            $repository = $this->getVideoRepository();
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $commentsCount = count($video->getVideoComments()) - self::COMMENTS_LIMIT_ON_SHOW_VIDEO_PAGE;
            $comments      = $this->getVideoCommentRepository()
                                  ->getVideoComments($video, self::COMMENTS_LIMIT_ON_SHOW_VIDEO_PAGE, $commentsCount);
            $comments      = array_reverse($comments);

            $html = $this->renderView('ZnaikaFrontendBundle:Video:video_comments.html.twig', array(
                'comments' => $comments
            ));

            $array = array(
                'html'    => $html,
                'success' => true
            );

            return new JsonResponse($array);
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

        /**
         * @return VideoCommentRepository
         */
        private function getVideoCommentRepository()
        {
            return $this->get('znaika_frontend.video_comment_repository');
        }

        /**
         * @return UserRepository
         */
        private function getUserRepository()
        {
            return $this->get('znaika_frontend.user_repository');
        }

        /**
         * @param $videoComment
         * @param $videoCommentRepository
         */
        private function prepareVideoCommentType(VideoComment $videoComment, VideoCommentRepository $videoCommentRepository)
        {
            $request = $this->getRequest();

            $videoComment->setCommentType(VideoCommentUtil::SIMPLE_COMMENT);
            $isQuestion = $request->get("isQuestion", false);
            $isAnswer   = $request->get("isAnswer", false);
            if ($isQuestion)
            {
                $videoComment->setCommentType(VideoCommentUtil::QUESTION);
            }
            elseif ($isAnswer)
            {
                $videoComment->setCommentType(VideoCommentUtil::ANSWER);

                $questionId = $request->get("questionId", 0);
                $question   = $videoCommentRepository->getOneByVideoCommentId($questionId);
                $question->setIsAnswered(true);
                $videoComment->setQuestion($question);
            }
        }

        /**
         * @param $video
         *
         * @return array|\Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment[]
         */
        private function getLastVideoComments($video)
        {
            $comments = $this->getVideoCommentRepository()
                             ->getLastVideoComments($video, self::COMMENTS_LIMIT_ON_SHOW_VIDEO_PAGE);
            $comments = array_reverse($comments);

            return $comments;
        }

        /**
         * @param $video
         *
         * @return null|\Znaika\FrontendBundle\Entity\Profile\Action\ViewVideoOperation
         */
        private function saveViewVideoOperation($video)
        {
            $user               = $this->getUser();
            $listener           = $this->getUserOperationListener();
            $viewVideoOperation = ($user) ? $listener->onViewVideo($user, $video) : null;

            return $viewVideoOperation;
        }

        /**
         * @return \Symfony\Component\Form\Form
         */
        private function getAddVideoCommentForm()
        {
            $videoComment        = new VideoComment();
            $addVideoCommentForm = $this->createForm(new VideoCommentType(), $videoComment);

            return $addVideoCommentForm;
        }

        /**
         * @return QuizAttemptRepository
         */
        private function getQuizAttemptRepository()
        {
            return $this->get("znaika_frontend.quiz_attempt_repository");
        }

        /**
         * @param User $user
         * @param Quiz $quiz
         *
         * @return QuizAttempt
         */
        private function getUserQuizAttempt(User $user, Quiz $quiz)
        {
            $quizAttemptRepository = $this->getQuizAttemptRepository();
            $quizAttempt           = $quizAttemptRepository->getUserQuizAttempt($user->getUserId(), $quiz->getQuizId());

            return $quizAttempt;
        }

        /**
         * @param $video
         *
         * @return mixed|null
         */
        private function getCurrentUserQuizScore(Video $video)
        {
            $userQuizScore = null;
            $user          = $this->getUser();
            $quiz          = $video->getQuiz();
            if ($user && $quiz)
            {
                $quizAttempt = $this->getUserQuizAttempt($this->getUser(), $video->getQuiz());
                if ($quizAttempt)
                {
                    $userQuizScore = $quizAttempt->getScore();
                }
            }

            return $userQuizScore;
        }
    }
