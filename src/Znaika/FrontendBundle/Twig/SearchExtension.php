<?php

    namespace Znaika\FrontendBundle\Twig;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Helper\Search\UserSearch;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\FrontendBundle\Helper\Util\UserSearchAgeUtil;
    use Znaika\FrontendBundle\Repository\Lesson\Category\SubjectRepository;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Helper\Util\UserRole;
    use Znaika\ProfileBundle\Helper\Util\UserSex;
    use Znaika\ProfileBundle\Repository\RegionRepository;

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
                'search_form'               => new \Twig_Function_Method($this, 'renderSearchForm'),
                'search_grade_filter'       => new \Twig_Function_Method($this, 'renderSearchGradeFilter'),
                'search_subject_filter'     => new \Twig_Function_Method($this, 'renderSearchSubjectFilter'),
                'search_user_region_filter' => new \Twig_Function_Method($this, 'renderSearchUserRegionFilter'),
                'search_user_age_filter'    => new \Twig_Function_Method($this, 'renderSearchUserAgeFilter'),
                'search_user_sex_filter'    => new \Twig_Function_Method($this, 'renderSearchUserSexFilter'),
                'search_user_role_filter'   => new \Twig_Function_Method($this, 'renderSearchUserRoleFilter'),
                'search_user_role'          => new \Twig_Function_Method($this, 'showSearchUserRole'),
            );
        }

        public function renderSearchForm()
        {
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

            $subject = $request->get("s", "");
            /** @var SubjectRepository $subjectRepository */
            $subjectRepository = $this->container->get("znaika.subject_repository");
            $currentSubject    = $subjectRepository->getOneByUrlName($subject);

            $templateFile    = "ZnaikaFrontendBundle:Search:subject_filter.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);

            $subjects = $subjectRepository->getAll();

            $result = $templateContent->render(array(
                "subjects"       => $subjects,
                "currentSubject" => $currentSubject,
            ));

            return $result;
        }

        public function renderSearchUserRegionFilter()
        {
            $request = $this->container->get("request");
            /** @var RegionRepository $regionRepository */
            $regionRepository = $this->container->get("znaika.region_repository");
            $currentRegionId  = $request->get(UserSearch::REGION_REQUEST_PARAM);

            $templateFile    = "ZnaikaFrontendBundle:Search:user_region_filter.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);

            $regions = $regionRepository->getAll();

            $result = $templateContent->render(array(
                "regions"         => $regions,
                "currentRegionId" => $currentRegionId,
            ));

            return $result;
        }

        public function renderSearchUserAgeFilter()
        {
            $request        = $this->container->get("request");
            $currentAgeFrom = $request->get(UserSearch::AGE_FROM_REQUEST_PARAM, "");
            $currentAgeTo   = $request->get(UserSearch::AGE_TO_REQUEST_PARAM, "");

            $templateFile    = "ZnaikaFrontendBundle:Search:user_age_filter.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);

            $agesFrom = UserSearchAgeUtil::getAvailableAgeFrom();
            $agesTo   = UserSearchAgeUtil::getAvailableAgeTo();

            $result = $templateContent->render(array(
                "agesFrom"       => $agesFrom,
                "agesTo"         => $agesTo,
                "currentAgeFrom" => $currentAgeFrom,
                "currentAgeTo"   => $currentAgeTo,
            ));

            return $result;
        }

        public function renderSearchUserSexFilter()
        {
            $request    = $this->container->get("request");
            $currentSex = $request->get(UserSearch::SEX_REQUEST_PARAM, "");

            $templateFile    = "ZnaikaFrontendBundle:Search:user_sex_filter.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);

            $values = UserSex::getAvailableTypesTexts();

            $result = $templateContent->render(array(
                "values"     => $values,
                "currentSex" => $currentSex,
            ));

            return $result;
        }

        public function renderSearchUserRoleFilter()
        {
            $request     = $this->container->get("request");
            $currentRole = $request->get(UserSearch::ROLE_REQUEST_PARAM, "");

            $templateFile    = "ZnaikaFrontendBundle:Search:user_role_filter.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);

            $availableRoles = array(
                UserRole::ROLE_USER    => "Учеников",
                UserRole::ROLE_PARENT  => "Родителей",
                UserRole::ROLE_TEACHER => "Учителей",
            );

            $result = $templateContent->render(array(
                "roles"       => $availableRoles,
                "currentRole" => $currentRole,
            ));

            return $result;
        }

        public function showSearchUserRole(User $user)
        {
            $role = $user->getRole();

            switch ($role)
            {
                case UserRole::ROLE_USER:
                    $result = "Ученик";
                    break;
                case UserRole::ROLE_TEACHER:
                    $result = "Учитель";
                    break;
                case UserRole::ROLE_PARENT:
                    $result = "Родитель";
                    break;
                default:
                    $result = "";
            }

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
