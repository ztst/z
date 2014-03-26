<?php
    namespace Znaika\FrontendBundle\Menu;

    use Knp\Menu\FactoryInterface;
    use Knp\Menu\ItemInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\SecurityContextInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\FrontendBundle\Helper\Util\Lesson\SubjectUtil;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;
    use Znaika\FrontendBundle\Repository\Lesson\Category\ISubjectRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoCommentRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class MenuBuilder
    {
        private $factory;

        /**
         * @var SecurityContextInterface
         */
        private $securityContext;

        /**
         * @var User
         */
        private $currentUser;

        private $currentRoute;

        /**
         * @param FactoryInterface $factory
         * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
         */
        public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext)
        {
            $this->factory         = $factory;
            $this->securityContext = $securityContext;

            $currentUser       = $this->securityContext->getToken()->getUser();
            $this->currentUser = $currentUser instanceof User ? $currentUser : null;
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
                $menuItem = $menu->addChild("<div class='class-menu-top-line'></div><span class='grade-number'>$classNumber</span><span class='grade-word'>&nbsp;класс</span><span class='arrow'></span>");
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
            $menu = $this->factory->createItem("root");
            $menu->setCurrentUri($request->getRequestUri());

            $menu->setChildrenAttribute("class", "profile-sidebar-menu");
            $this->currentRoute = $request->get('_route');

            $countQuestions = $videoCommentRepository->countTeacherNotAnsweredQuestionComments($this->currentUser);
            $userId         = $this->currentUser->getUserId();
            $menu->addChild("Мой профиль",
                array("route" => "edit_teacher_profile", "routeParameters" => array("userId" => $userId)));

            $title = "Вопросы к урокам";
            $title .= $countQuestions ? " <span class='user-questions-count'>+$countQuestions</span>" : "";
            $menuItem = $menu->addChild($title,
                array("route" => "teacher_questions", "routeParameters" => array("userId" => $userId)));
            $menuItem->setExtra('safe_label', true);

            if ($this->securityContext->isGranted(UserRole::getSecurityTextByRole(UserRole::ROLE_MODERATOR)))
            {
                $menu = $this->addModeratorItems($menu, $videoCommentRepository, $userRepository);
            }

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
            $currentClass = $request->get("class", null);

            return $currentClass ? $currentClass : 1; //TODO: add user class by default
        }

        protected function getCurrentSubjectUrlName(Request $request)
        {
            return $request->get("subjectName", "");
        }

        private function addModeratorItems(ItemInterface $menu, VideoCommentRepository $videoCommentRepository, UserRepository $userRepository)
        {
            $this->addCommentMenuItem($menu, $videoCommentRepository);
            $this->addPupilMenuItem($menu, $userRepository);

            return $menu;
        }

        private function addCommentMenuItem(ItemInterface $menu, VideoCommentRepository $videoCommentRepository)
        {
            $countComments = $videoCommentRepository->countModeratorNotVerifiedComments();

            $title = "Комментарии";
            $title .= $countComments ? " <span class='not-verified-comments-count'>+$countComments</span>" : "";
            $menuItem = $menu->addChild($title,
                array("route" => "not_verified_comments", "routeParameters" => array("userId" => $this->currentUser->getUserId())));
            $menuItem->setExtra('safe_label', true);

            return $menu;
        }

        private function addPupilMenuItem(ItemInterface $menu, UserRepository $userRepository)
        {
            $countPupils = $userRepository->countNotVerifiedUsers(array(UserRole::ROLE_USER));

            $title = "Ученики";
            $title .= $countPupils ? " <span class='not-verified-pupils-count'>+$countPupils</span>" : "";
            $menuItem = $menu->addChild($title, array("route" => "not_verified_pupils"));
            if ($this->currentRoute == "not_verified_pupils")
            {
                $menuItem->setCurrent(true);
            }
            $menuItem->setExtra('safe_label', true);

            return $menu;
        }
    }