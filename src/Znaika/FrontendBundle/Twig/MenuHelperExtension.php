<?php

    namespace Znaika\FrontendBundle\Twig;

    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\FrontendBundle\Repository\Lesson\Category\SubjectRepository;

    class MenuHelperExtension extends \Twig_Extension
    {
        /**
         * @var SubjectRepository
         */
        private $subjectRepository;

        public function __construct(SubjectRepository $subjectRepository)
        {
            $this->subjectRepository = $subjectRepository;
        }

        public function getFunctions()
        {
            return array(
                'grades_with_subjects_json' => new \Twig_Function_Method($this, 'renderGradesWithSubjects'),
            );
        }

        public function renderGradesWithSubjects()
        {
            $grades = ClassNumberUtil::getAvailableClasses();

            $result = array();
            foreach ($grades as $grade)
            {
                $subjects = $this->subjectRepository->getByGrade($grade);

                $subjectsUrlNames = $this->prepareSubjectsUrlNames($subjects);

                $result[$grade] = $subjectsUrlNames;
            }

            return json_encode($result);
        }

        private function prepareSubjectsUrlNames($subjects)
        {
            $subjectsUrlNames = array();
            foreach ($subjects as $subject)
            {
                array_push($subjectsUrlNames, $subject->getUrlName());
            }

            return $subjectsUrlNames;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_menu_helper_extension';
        }
    }
