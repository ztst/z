<?php

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion;
    use Znaika\FrontendBundle\Form\Lesson\Content\Quiz\Attempt\UserAttemptType;
    use Znaika\FrontendBundle\Form\Lesson\Content\Quiz\QuizQuestionType;

    class QuizController extends Controller
    {
        public function addQuizAttemptAction(Request $request)
        {
            $userAttempt = $this->prepareUserAttempt($request->get('videoName'));

            $form = $this->createForm(new UserAttemptType(), $userAttempt);
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $userAttemptRepository = $this->get('user_attempt_repository');
                $userAttemptRepository->save($userAttempt);

                return $this->render('ZnaikaFrontendBundle:Quiz:addQuizAttempt.html.twig', array(
                    'userAttempt'         => $userAttempt,
                    'userQuestionAnswers' => $userAttempt->getUserQuestionAnswers(),
                ));
            }

            throw $this->createNotFoundException("Can't add user quiz attempt");
        }

        public function showQuizAction($videoName)
        {
            $userAttempt = $this->prepareUserAttempt($videoName);

            $form = $this->createForm(new UserAttemptType(), $userAttempt);

            return $this->render('ZnaikaFrontendBundle:Quiz:showQuiz.html.twig', array(
                'videoName' => $videoName,
                'form'      => $form->createView(),
            ));
        }

        private function prepareUserAttempt($videoName)
        {
            $repository = $this->get("video_repository");
            $video      = $repository->getOneByUrlName($videoName);

            $user = $this->getUser();

            $userAttempt = new UserAttempt();
            $userAttempt->setUser($user);
            $userAttempt->setVideo($video);

            $questions = $video->getQuizQuestions();

            foreach ($questions as $question)
            {
                $questionAnswer = new UserQuestionAnswer();
                $questionAnswer->setQuizQuestion($question);
                $userAttempt->addUserQuestionAnswer($questionAnswer);
            }

            return $userAttempt;
        }

        public function addQuizFormAction(Request $request)
        {
            $repository = $this->get("video_repository");
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $quizQuestion = new QuizQuestion();
            $form         = $this->createForm(new QuizQuestionType(), $quizQuestion);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $quizQuestion->setVideo($video);
                $video->addQuizQuestion($quizQuestion);

                $quizQuestionRepository = $this->get('quiz_question_repository');
                $quizQuestionRepository->save($quizQuestion);

                return new RedirectResponse($this->generateUrl('show_video', array(
                    "class"       => $video->getGrade(),
                    "subjectName" => $video->getSubject()->getUrlName(),
                    "videoName"   => $video->getUrlName()
                )));
            }

            return $this->render('ZnaikaFrontendBundle:Quiz:addQuizForm.html.twig', array(
                'form'  => $form->createView(),
                'video' => $video,
            ));
        }

    }
