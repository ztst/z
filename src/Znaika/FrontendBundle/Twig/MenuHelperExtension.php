<?php

    namespace Znaika\FrontendBundle\Twig;

    use Znaika\FrontendBundle\Helper\Util\Lesson\SubjectUtil;
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
            $result = SubjectUtil::getGradesSubjects();

            return json_encode($result);
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_menu_helper_extension';
        }
    }
