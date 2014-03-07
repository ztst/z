<?php

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Stat\QuizAttempt;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Form\Lesson\Content\Attachment\QuizType;
    use Znaika\FrontendBundle\Helper\Uploader\QuizUploader;
    use Znaika\FrontendBundle\Repository\Lesson\Content\Attachment\QuizRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\Stat\QuizAttemptRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;

    class QuizController extends Controller
    {
        public function saveQuizStatAction(Request $request)
        {
            $referrer = $request->headers->get('referer');
            $pattern  = '/quiz_content\/([^\/]*)\//i';
            if (!preg_match($pattern, $referrer, $matches))
            {
                return $this->createNotFoundException("Bad url for save quiz stat");
            }

            $videoUrlName = $matches[1];
            $video     = $this->getVideoRepository()->getOneByUrlName($videoUrlName);
            if (is_null($video) || !$video->getQuiz())
            {
                return $this->createNotFoundException("Quiz not found");
            }

            $user = $this->getUser();
            if (is_null($user))
            {
                return $this->createNotFoundException("Not logged user view quiz");
            }

            $quiz = $video->getQuiz();
            $quizAttemptRepository = $this->getQuizAttemptRepository();
            $quizAttempt           = $this->prepareUserQuizAttempt($user, $quiz);
            $quizAttemptRepository->save($quizAttempt);

            $array = array(
                'success' => true
            );

            return new JsonResponse($array);
        }

        public function addQuizFormAction($videoName)
        {
            $videoRepository = $this->getVideoRepository();
            $video           = $videoRepository->getOneByUrlName($videoName);
            $quiz            = is_null($video->getQuiz()) ? new Quiz() : $video->getQuiz();
            $form            = $this->createForm(new QuizType(), $quiz);

            $form->handleRequest($this->getRequest());

            if ($form->isValid())
            {
                $quiz->setVideo($video);
                $video->setQuiz($quiz);

                /** @var QuizUploader $uploader */
                $uploader = $this->get('znaika_frontend.quiz_uploader');
                $uploader->upload($quiz);

                $this->getQuizRepository()->save($quiz);

                return new RedirectResponse($this->generateUrl('show_video', array(
                    "class"       => $video->getGrade(),
                    "subjectName" => $video->getSubject()->getUrlName(),
                    "videoName"   => $video->getUrlName()
                )));
            }

            return $this->render('ZnaikaFrontendBundle:Video:addQuizForm.html.twig', array(
                "form"  => $form->createView(),
                "video" => $video,
            ));
        }

        /**
         * @return QuizRepository
         */
        private function getQuizRepository()
        {
            return $this->get("znaika_frontend.quiz_repository");
        }

        /**
         * @param User $user
         * @param Quiz $quiz
         *
         * @return QuizAttempt
         */
        private function prepareUserQuizAttempt(User $user, Quiz $quiz)
        {
            $quizAttempt = $this->getUserQuizAttempt($user, $quiz);
            $quizAttempt->setQuiz($quiz);
            $quizAttempt->setUser($user);
            $quizAttempt->setCreatedTime(new \DateTime());
            $this->setQuizAttemptScore($quizAttempt);

            return is_null($quizAttempt) ? new QuizAttempt() : $quizAttempt;
        }

        /**
         * @param $quizAttempt
         */
        private function setQuizAttemptScore(QuizAttempt $quizAttempt)
        {
            $request    = $this->getRequest();
            $userPoint  = $request->get("sp", 0);
            $totalPoint = $request->get("tp", 1);
            $score      = $userPoint * 100 / $totalPoint;
            $quizAttempt->setScore($score);
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
         * @return QuizAttemptRepository
         */
        private function getQuizAttemptRepository()
        {
            return $this->get("znaika_frontend.quiz_attempt_repository");
        }

        /**
         * @return VideoRepository
         */
        private function getVideoRepository()
        {
            return $this->get("znaika_frontend.video_repository");
        }
    }
