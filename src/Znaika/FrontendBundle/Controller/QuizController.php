<?php

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion;
    use Znaika\FrontendBundle\Form\Lesson\Content\Quiz\QuizQuestionType;

    class QuizController extends Controller
    {
        public function showQuizAction($videoName)
        {
            $repository = $this->get("video_repository");
            $video      = $repository->getOneByUrlName($videoName);

            return $this->render('ZnaikaFrontendBundle:Quiz:showQuiz.html.twig', array(
                'quizQuestions'=> $video->getQuizQuestions(),
            ));
        }

        public function addQuizFormAction(Request $request)
        {
            $repository = $this->get("video_repository");
            $video      = $repository->getOneByUrlName($request->get('videoName'));

            $quizQuestion = new QuizQuestion();
            $form  = $this->createForm(new QuizQuestionType(), $quizQuestion);

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $quizQuestion->setVideo($video);
                $video->addQuizQuestion($quizQuestion);

                $quizQuestionRepository = $this->get('quiz_question_repository');
                $quizQuestionRepository->save($quizQuestion);

                return new RedirectResponse($this->generateUrl('show_video', array(
                    "class" => $video->getGrade(),
                    "subjectName" => $video->getSubject()->getUrlName(),
                    "videoName" => $video->getUrlName()
                )));
            }
            return $this->render('ZnaikaFrontendBundle:Quiz:addQuizForm.html.twig', array(
                'form' => $form->createView(),
                'video'=> $video,
            ));
        }

    }
