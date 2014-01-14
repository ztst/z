<?php
    namespace Znaika\FrontendBundle\Menu;

    use Knp\Menu\FactoryInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\FrontendBundle\Repository\Lesson\Category\ISubjectRepository;

    class MenuBuilder
    {
        private $factory;

        /**
         * @param FactoryInterface $factory
         */
        public function __construct(FactoryInterface $factory)
        {
            $this->factory = $factory;
        }

        public function createMainMenu(Request $request)
        {
            $menu = $this->factory->createItem("root");
            $menu->setChildrenAttribute("class", "nav navbar-nav main_menu");

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
                $menuItem = $menu->addChild("<span class='grade_number'>$classNumber</span> класс");
                $menuItem->setExtra('safe_label', true);
                $menuItem->setAttribute("id", $classNumber);

                if ($currentClass && $currentClass == $classNumber)
                {
                    $menuItem->setAttribute("class", "selected");
                }
            }

            return $menu;
        }

        public function createSidebarSubjectMenu(Request $request, ISubjectRepository $repository)
        {
            $currentGrade = $this->getCurrentClass($request);

            $menu = $this->factory->createItem("root");
            $menu->setChildrenAttribute("class", "nav nav-justified");

            $currentSubjects = $repository->getByGrade($currentGrade);
            $subjects = $repository->getAll();
            foreach ($subjects as $subject)
            {
                $menuItem = $menu->addChild($subject->getName(), array());
                $menuItem->setAttribute("id", $subject->getUrlName());

                if (!in_array($subject, $currentSubjects))
                {
                    $menuItem->setAttribute("class", "hidden");
                }
            }

            return $menu;
        }

        protected function getCurrentClass(Request $request)
        {
            $currentClass = $request->get("class", null);

            return $currentClass ? $currentClass : 1; //TODO: add user class by default
        }
    }