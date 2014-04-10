<?php

    namespace Znaika\FrontendBundle\Twig;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\FrontendBundle\Repository\Lesson\Category\SubjectRepository;

    class SearchExtension extends \Twig_Extension
    {
        /**
         * @var ContainerInterface
         */
        private $container;

        /**
         * @var \Twig_Environment
         */
        private $twig;

        public function __construct(\Twig_Environment $twig, ContainerInterface $container)
        {
            $this->twig      = $twig;
            $this->container = $container;
        }

        public function getFunctions()
        {
            return array(
                'search_form'         => new \Twig_Function_Method($this, 'renderSearchForm'),
                'search_grade_filter' => new \Twig_Function_Method($this, 'renderSearchGradeFilter'),
                'search_subject_filter' => new \Twig_Function_Method($this, 'renderSearchSubjectFilter'),
            );
        }

        public function renderSearchForm()
        {
            $result  = "";
            $request = $this->container->get("request");

            $searchString = $request->get("q", "");
            $subject      = $request->get("s", "");
            $grade        = $request->get("g", "");

            $templateFile    = "ZnaikaFrontendBundle:Search:search_form.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);

            $result = $templateContent->render(array(
                "searchString" => $searchString,
                "subject"      => $subject,
                "grade"        => $grade,
            ));

            return $result;
        }

        public function renderSearchGradeFilter()
        {
            $request = $this->container->get("request");

            $grade = $request->get("g", "");
            $grade = ClassNumberUtil::isValidClassNumber($grade) ? $grade : "";

            $templateFile    = "ZnaikaFrontendBundle:Search:grade_filter.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);

            $grades = ClassNumberUtil::getAvailableClasses();

            $result = $templateContent->render(array(
                "grades"       => $grades,
                "currentGrade" => $grade,
            ));

            return $result;
        }

        public function renderSearchSubjectFilter()
        {
            $request = $this->container->get("request");

            $subject      = $request->get("s", "");
            /** @var SubjectRepository $subjectRepository */
            $subjectRepository = $this->container->get("znaika.subject_repository");
            $currentSubject = $subjectRepository->getOneByUrlName($subject);

            $templateFile    = "ZnaikaFrontendBundle:Search:subject_filter.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);

            $subjects = $subjectRepository->getAll();

            $result = $templateContent->render(array(
                "subjects"       => $subjects,
                "currentSubject" => $currentSubject,
            ));

            return $result;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_search_extension';
        }
    }
