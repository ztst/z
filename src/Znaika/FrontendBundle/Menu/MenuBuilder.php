<?php
    namespace Znaika\FrontendBundle\Menu;

    use Knp\Menu\ItemInterface;
    use Knp\Menu\FactoryInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\SecurityContextInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;
    use Znaika\FrontendBundle\Helper\Util\UserEditProfileUrlUtil;
    use Znaika\FrontendBundle\Repository\Communication\MessageRepository;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\FrontendBundle\Helper\Util\Lesson\SubjectUtil;
    use Znaika\ProfileBundle\Helper\Util\UserRole;
    use Znaika\FrontendBundle\Repository\Lesson\Category\ISubjectRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoCommentRepository;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class MenuBuilder
    {
        const DEFAULT_CLASS_FOR_ANONYMOUS_USER = 5;
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

            $token = $this->securityContext->getToken();
            $currentUser       = $token ? $token->getUser() : null;
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
                $menuItem = $menu->addChild(
                                 "<span class='class-menu-top-line'></span>
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

        public function createSidebarProfileMenu(Request $request, UserRepository $userRepository,
                                                 VideoCommentRepository $videoCommentRepository, MessageRepository $messageRepository)
        {
            $menu = $this->factory->createItem("root");
            $menu->setCurrentUri($request->getRequestUri());

            $menu->setChildrenAttribute("class", "profile-sidebar-menu");
            $this->currentRoute = $request->get('_route');

            $userId         = $this->currentUser->getUserId();
            $urlPattern     =  UserEditProfileUrlUtil::prepareUrlPatterByRole($this->currentUser->getRole());
            $menu->addChild("Мой профиль",
                array("route" => $urlPattern, "routeParameters" => array("userId" => $userId)));

            $countThreads = $messageRepository->countUnreadThreadsByParticipant($this->currentUser);
            $title = "Общение";
            $title .= $countThreads ? " <span class='list-count-container not-read-threads-count'>+$countThreads</span>" : "";
            $menu->addChild($title, array("route" => "show_threads"));

            if ($this->securityContext->isGranted(UserRole::getSecurityTextByRole(UserRole::ROLE_TEACHER)))
            {
                $this->addTeacherItems($videoCommentRepository, $menu, $userId);
            }

            if ($this->securityContext->isGranted(UserRole::getSecurityTextByRole(UserRole::ROLE_MODERATOR)))
            {
                $this->addModeratorItems($menu, $videoCommentRepository, $userRepository);
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
            $defaultUserClass = ($this->currentUser instanceof User) ? $this->currentUser->getGrade() : null;
            $currentClass = $request->get("class", $defaultUserClass);

            return $currentClass ? $currentClass : self::DEFAULT_CLASS_FOR_ANONYMOUS_USER;
        }

        protected function getCurrentSubjectUrlName(Request $request)
        {
            return $request->get("subjectName", "");
        }

        private function addModeratorItems(ItemInterface $menu, VideoCommentRepository $videoCommentRepository, UserRepository $userRepository)
        {
            $this->addCommentMenuItem($menu, $videoCommentRepository);
            $this->addPupilMenuItem($menu, $userRepository);
        }

        private function addCommentMenuItem(ItemInterface $menu, VideoCommentRepository $videoCommentRepository)
        {
            $countComments = $videoCommentRepository->countModeratorNotVerifiedComments();

            $title = "Комментарии";
            $title .= $countComments ? " <span class='list-count-container not-verified-comments-count'>+$countComments</span>" : "";
            $menuItem = $menu->addChild($title,
                array("route" => "not_verified_comments", "routeParameters" => array("userId" => $this->currentUser->getUserId())));
            $menuItem->setExtra('safe_label', true);

            return $menu;
        }

        private function addPupilMenuItem(ItemInterface $menu, UserRepository $userRepository)
        {
            $countPupils = $userRepository->countNotVerifiedUsers(array(UserRole::ROLE_USER));

            $title = "Ученики";
            $title .= $countPupils ? " <span class='list-count-container not-verified-pupils-count'>+$countPupils</span>" : "";
            $menuItem = $menu->addChild($title, array("route" => "not_verified_pupils"));
            if ($this->currentRoute == "not_verified_pupils")
            {
                $menuItem->setCurrent(true);
            }
            $menuItem->setExtra('safe_label', true);

            return $menu;
        }

        /**
         * @param VideoCommentRepository $videoCommentRepository
         * @param \Knp\Menu\ItemInterface $menu
         * @param $userId
         */
        private function addTeacherItems($videoCommentRepository, ItemInterface $menu, $userId)
        {
            $countQuestions = $videoCommentRepository->countTeacherNotAnsweredQuestionComments($this->currentUser);
            $title = "Вопросы к урокам";
            $title .= $countQuestions ? " <span class='list-count-container user-questions-count'>+$countQuestions</span>" : "";
            $menuItem = $menu->addChild($title,
                array("route" => "teacher_questions", "routeParameters" => array("userId" => $userId)));
            $menuItem->setExtra('safe_label', true);
        }
    }