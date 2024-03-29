<?
    namespace Znaika\FrontendBundle\Helper\Search;

    use Symfony\Component\HttpFoundation\Request;
    use Znaika\ProfileBundle\Helper\Util\TeacherExperienceUtil;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class UserSearch extends SphinxSearch
    {
        const REGION_FIELD          = "region_id";
        const USER_INDEX_NAME       = "znaika_user";
        const BIRTHDAY_FIELD        = "birthday_ts";
        const SEX_FIELD             = "sex";
        const ROLE_FIELD            = "role";
        const EXPERIENCE_FIELD      = "teacher_experience";
        const TEACHER_SUBJECT_FIELD = "subject_id";

        const REGION_REQUEST_PARAM          = "r";
        const AGE_FROM_REQUEST_PARAM        = "af";
        const AGE_TO_REQUEST_PARAM          = "at";
        const SEX_REQUEST_PARAM             = "s";
        const ROLE_REQUEST_PARAM            = "rl";
        const EXPERIENCE_FROM_REQUEST_PARAM = "ef";
        const EXPERIENCE_TO_REQUEST_PARAM   = "et";
        const SUBJECT_REQUEST_PARAM         = "sb";
        /**
         * @var UserRepository
         */
        private $userRepository;

        private $users;

        private $region;
        private $sex;
        private $role;
        private $ageFrom;
        private $ageTo;
        private $experienceFrom;
        private $experienceTo;
        private $subject;

        public function __construct(UserRepository $videoRepository)
        {
            parent::__construct();

            $this->userRepository = $videoRepository;
        }

        public function getUsersBySearchString($searchString, $limit = null, $page = null)
        {
            if (!$this->users)
            {
                if (!$this->prepareSearchingUserResult($searchString, $limit, $page))
                {
                    throw new \Exception("Sphinx Error: " . $this->error);
                }
            }

            $this->users = $this->userRepository->getByUserIds($this->ids);

            return $this->users;
        }

        public function countUserBySearchString($searchString)
        {
            if (!$this->totalFound)
            {
                if (!$this->prepareSearchingUserResult($searchString))
                {
                    throw new \Exception("Sphinx Error: " . $this->error);
                }
            }

            return $this->totalFound;
        }

        public function initFromRequest(Request $request)
        {
            $region = $request->get(self::REGION_REQUEST_PARAM);
            if ($region)
            {
                $this->region = $region;
            }

            $ageFrom = $request->get(self::AGE_FROM_REQUEST_PARAM);
            $ageTo   = $request->get(self::AGE_TO_REQUEST_PARAM);
            if ($ageFrom || $ageTo)
            {
                $this->ageFrom = $ageFrom ? strtotime("-" . $ageFrom . " year") : time();
                $this->ageTo   = $ageTo ? strtotime("-" . $ageTo . " year") : 0;
            }

            $sex = $request->get(self::SEX_REQUEST_PARAM);
            if ($sex)
            {
                $this->sex = $sex;
            }

            $this->role = $request->get(self::ROLE_REQUEST_PARAM, null);

            $experienceFrom = $request->get(self::EXPERIENCE_FROM_REQUEST_PARAM);
            $experienceTo   = $request->get(self::EXPERIENCE_TO_REQUEST_PARAM);
            if ($experienceFrom || $experienceTo)
            {
                $this->experienceFrom = $experienceFrom ? $experienceFrom : TeacherExperienceUtil::MIN_EXPERIENCE;
                $this->experienceTo   = $experienceTo ? $experienceTo : TeacherExperienceUtil::MAX_EXPERIENCE;
            }

            $this->subject = $request->get(self::SUBJECT_REQUEST_PARAM, null);
        }

        public function setRole($role)
        {
            $this->role = $role;
        }

        protected function getIndexName()
        {
            return self::USER_INDEX_NAME;
        }

        private function prepareSearchingUserResult($searchString, $limit = null, $page = null)
        {
            $this->prepareSphinxLimit($limit, $page);

            $this->prepareSphinxFilters();

            $result = $this->processSearchResult($searchString);

            return $result !== false;
        }

        private function prepareSphinxFilters()
        {
            if ($this->region)
            {
                $this->sphinx->SetFilter(self::REGION_FIELD, array(intval($this->region)));
            }
            if ($this->ageFrom || $this->ageTo)
            {
                $this->sphinx->SetFilterRange(self::BIRTHDAY_FIELD, $this->ageTo, $this->ageFrom);
            }
            if ($this->sex)
            {
                $this->sphinx->SetFilter(self::SEX_FIELD, array(intval($this->sex)));
            }
            if (!is_null($this->role))
            {
                $this->sphinx->SetFilter(self::ROLE_FIELD, array(intval($this->role)));
            }
            if ($this->experienceTo && $this->experienceFrom)
            {
                $this->sphinx->SetFilterRange(self::EXPERIENCE_FIELD, intval($this->experienceFrom), intval($this->experienceTo));
            }
            if ($this->subject)
            {
                $this->sphinx->SetFilter(self::TEACHER_SUBJECT_FIELD, array(intval($this->subject)));
            }
        }

        private function prepareSphinxLimit($limit, $page)
        {
            if ($limit)
            {
                $page = $page ? $page : 0;
                $this->sphinx->SetLimits($page * $limit, $limit);
            }
        }
    }