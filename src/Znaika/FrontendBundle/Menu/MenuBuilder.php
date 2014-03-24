<?php
    namespace Znaika\FrontendBundle\Menu;

    use Knp\Menu\FactoryInterface;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\SecurityContext;
    use Symfony\Component\Security\Core\SecurityContextInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\FrontendBundle\Helper\Util\Lesson\SubjectUtil;
    use Znaika\FrontendBundle\Repository\Lesson\Category\ISubjectRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoCommentRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class MenuBuilder
    {
        const ANONYMOUS_USER_DEFAULT_CLASS = 5;
        private $factory;

        /**
         * @param FactoryInterface $factory
         */

        /**
         * @var ContainerInterface
         */
        private $container;

        public function __construct(FactoryInterface $factory, ContainerInterface $container)
        {
            $this->factory   = $factory;
            $this->container = $container;
        }

        public function createMainMenu()
        {
            $menu = $this->factory->createItem("root");
            $menu->setChildrenAttribute("class", "nav navbar-nav main-menu");

            $menu->addChild("ВИДЕОУРОКИ", array("route" => "add_video_form"));
            $menu->addChild("КОНСПЕКТЫ", array("route" => "znaika_frontend_homepage"));
            $menu->addChild("ТЕСТЫ", array("route" => "znaika_frontend_homepage"));

            return $menu;
        }

        public function createSidebarGradeMenu(Request $request)
        {
            $currentClass = $this->getCurrentClass($request);

            $menu = $this->factory->createItem("root");
            $menu->setChildrenAttribute("class", "nav nav-justified");

            $classes = ClassNumberUtil::getAvailableClasses();
            foreach ($classes as $classNumber)
            {
                $menuItem = $menu->addChild(
                                 "<div class='class-menu-top-line'></div>
                                  <span class='grade-number'>$classNumber</span>
                                  <span class='grade-word'>&nbsp;класс</span>
                                  <span class='arrow'></span>"
                );
                $menuItem->setExtra('safe_label', true);
                $menuItem->setAttribute("id", $classNumber);

                if ($currentClass && $currentClass == $classNumber)
                {
                    $menuItem->setAttribute("class", "selected");
                }
            }

            return $menu;
        }

        public function createSidebarProfileMenu(Request $request, UserRepository $userRepository, VideoCommentRepository $videoCommentRepository)
        {
            $userId = $request->get("userId");
            $menu   = $this->factory->createItem("root");
            $menu->setCurrentUri($request->getRequestUri());

            $menu->setChildrenAttribute("class", "profile-sidebar-menu");

            $countQuestions = $videoCommentRepository->countTeacherNotAnsweredQuestionComments($userRepository->getOneByUserId($userId));
            $menu->addChild("Мой профиль",
                array("route" => "edit_teacher_profile", "routeParameters" => array("userId" => $userId)));

            $title = "Вопросы к урокам";
            $title .= $countQuestions ? " <span class='user-questions-count'>(+$countQuestions)</span>" : "";
            $menuItem = $menu->addChild($title,
                array("route" => "teacher_questions", "routeParameters" => array("userId" => $userId)));
            $menuItem->setExtra('safe_label', true);

            return $menu;
        }

        public function createSidebarSubjectMenu(Request $request, ISubjectRepository $repository)
        {
            $currentGrade          = $this->getCurrentClass($request);
            $currentSubjectUrlName = $this->getCurrentSubjectUrlName($request);

            $menu = $this->factory->createItem("root");
            $menu->setChildrenAttribute("class", "subject-menu-list");

            $gradesSubjects  = SubjectUtil::getGradesSubjects();
            $currentSubjects = $gradesSubjects[$currentGrade];
            $subjects        = $repository->getAll();
            $subjects        = $this->prepareSubjectsOrder($subjects, $currentSubjects);
            foreach ($subjects as $subject)
            {
                /** @var Subject $subject */
                $menuItem = $menu->addChild($subject->getName(),
                    array("route"           => "show_catalogue",
                          "routeParameters" => array("class" => $currentGrade, "subjectName" => $subject->getUrlName())
                    )
                );

                $menuItem->setAttribute("id", $subject->getUrlName());

                if (!in_array($subject->getUrlName(), $currentSubjects))
                {
                    $menuItem->setAttribute("class", "hidden");
                }
                if ($currentSubjectUrlName && $currentSubjectUrlName == $subject->getUrlName())
                {
                    $menuItem->setAttribute("class", "selected");
                }

                $menuItem->setAttribute("class", $menuItem->getAttribute("class") . " " . $subject->getUrlName());
            }

            return $menu;
        }

        protected function prepareSubjectsOrder($subjects, $currentSubjects)
        {
            $result = array();
            foreach ($currentSubjects as $subjectUrlName)
            {
                $subject = $this->findSubjectByUrlName($subjects, $subjectUrlName);
                if (!is_null($subject))
                {
                    array_push($result, $subject);
                }
            }
            foreach ($subjects as $subject)
            {
                if (!in_array($subject, $result))
                {
                    array_push($result, $subject);
                }
            }

            return $result;
        }

        protected function findSubjectByUrlName($subjects, $urlName)
        {
            foreach ($subjects as $subject)
            {
                /** @var Subject $subject */
                if ($subject->getUrlName() == $urlName)
                {
                    return $subject;
                }
            }

            return null;
        }

        protected function getCurrentClass(Request $request)
        {
            /** @var SecurityContextInterface $securityContext */
            $securityContext = $this->container->get('security.context');
            $user = $securityContext->getToken()->getUser();

            $defaultUserClass = ($user instanceof User) ? $user->getGrade() : null;

            $currentClass = $request->get("class", $defaultUserClass);

            return $currentClass ? $currentClass : self::ANONYMOUS_USER_DEFAULT_CLASS;
        }

        protected function getCurrentSubjectUrlName(Request $request)
        {
            return $request->get("subjectName", "");
        }
    }