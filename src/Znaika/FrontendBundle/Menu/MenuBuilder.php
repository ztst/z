<?php
    namespace Znaika\FrontendBundle\Menu;

    use Knp\Menu\FactoryInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\FrontendBundle\Entity\Lesson\Category\ISubjectRepository;

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
            $menu->setChildrenAttribute("class", "nav navbar-nav");

            $menu->addChild("Видеоуроки", array("route" => "add_video_form"));
            $menu->addChild("Литература", array("route" => "znaika_frontend_homepage"));
            $menu->addChild("Поиск", array("route" => "znaika_frontend_homepage"));

            return $menu;
        }

        public function createSidebarGradeMenu(Request $request)
        {
            $menu = $this->factory->createItem("root");

            $classes = ClassNumberUtil::getAvailableClasses();

            foreach ( $classes as $classNumber )
            {
                $menuItem = $menu->addChild("$classNumber класс",
                    array(
                        "route" => "show_catalogue_by_class",
                        "routeParameters" => array(
                            "class" => $classNumber
                        )
                    )
                );
                $menuItem->setLinkAttribute("class", "list-group-item");

            }

            return $menu;
        }

        public function createSidebarSubjectMenu(Request $request, ISubjectRepository $repository)
        {
            $subjects = $repository->getAll();

            $menu = $this->factory->createItem("root");
            foreach ( $subjects as $subject )
            {
                $menuItem = $menu->addChild($subject->getName(),
                    array(
                        "route" => "show_catalogue_by_subject",
                        "routeParameters" => array(
                            "subjectName" => $subject->getUrlName()
                        )
                    )
                );
                $menuItem->setLinkAttribute("class", "list-group-item");
            }

            return $menu;
        }
    }