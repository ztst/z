<?php

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Helper\Search\UserSearch;
    use Znaika\FrontendBundle\Helper\Search\VideoSearch;
    use Znaika\FrontendBundle\Repository\Lesson\Category\SubjectRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\SynopsisRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;
    use Znaika\ProfileBundle\Helper\Util\UserRole;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class SearchController extends ZnaikaController
    {
        const RESULT_ON_SPECIAL_PAGE = 15;

        public function searchUsersAjaxAction(Request $request)
        {
            $searchString = trim($request->get("q", ""));
            $page         = intval($request->get("page"));
            $users        = $this->searchUsers($searchString, $request, null, self::RESULT_ON_SPECIAL_PAGE, $page);
            $countUsers   = $this->countFoundedUsers($searchString, $request);
            $isFinalPage  = $this->isFinalPage($countUsers, $page, self::RESULT_ON_SPECIAL_PAGE);

            $html = $this->renderView('ZnaikaFrontendBundle:Search:search_user_list.html.twig', array(
                'users' => $users,
            ));

            $array = array(
                'html'        => $html,
                'isFinalPage' => $isFinalPage,
                'countUsers'  => $countUsers,
            );

            return new JsonResponse($array);
        }

        public function searchVideoAction(Request $request)
        {
            $searchString = trim($request->get("q", ""));
            $subject      = $this->getSubjectIdFromRequest($request);
            $grade        = $request->get("g", "");
            $videos       = null;
            $countVideos  = 0;
            if ($searchString)
            {
                $videos      = $this->searchVideos($searchString, $subject, $grade, self::RESULT_ON_SPECIAL_PAGE);
                $countVideos = $this->countFoundedVideos($searchString, $subject, $grade);
            }
            $isFinalPage = $this->isFinalPage($countVideos, 0, self::RESULT_ON_SPECIAL_PAGE);

            return $this->render('ZnaikaFrontendBundle:Search:searchVideo.html.twig', array(
                'videos'       => $videos,
                'searchString' => $searchString,
                'subject'      => $subject,
                'grade'        => $grade,
                'isFinalPage'  => $isFinalPage,
                'countVideos'  => $countVideos,
            ));
        }

        public function searchVideoAjaxAction(Request $request)
        {
            $searchString = trim($request->get("q", ""));
            $subject      = $this->getSubjectIdFromRequest($request);
            $grade        = $request->get("g", "");
            $page         = intval($request->get("page"));
            $videos       = $this->searchVideos($searchString, $subject, $grade, self::RESULT_ON_SPECIAL_PAGE, $page);
            $countVideos  = $this->countFoundedVideos($searchString, $subject, $grade);
            $isFinalPage  = $this->isFinalPage($countVideos, $page, self::RESULT_ON_SPECIAL_PAGE);

            $html = $this->renderView('ZnaikaFrontendBundle:Search:search_video_list.html.twig', array(
                'videos' => $videos
            ));

            $array = array(
                'html'        => $html,
                'isFinalPage' => $isFinalPage
            );

            return new JsonResponse($array);
        }

        public function searchParentAction()
        {
            return $this->render('ZnaikaFrontendBundle:Search:searchParent.html.twig', array());
        }

        public function searchParentAjaxAction(Request $request)
        {
            $searchString = trim($request->get("q", ""));
            $page         = intval($request->get("page"));
            $users        = $this->searchUsers($searchString, $request, UserRole::ROLE_PARENT, self::RESULT_ON_SPECIAL_PAGE, $page);
            $countUsers   = $this->countFoundedUsers($searchString, $request, UserRole::ROLE_PARENT);
            $isFinalPage  = $this->isFinalPage($countUsers, $page, self::RESULT_ON_SPECIAL_PAGE);

            $html = $this->renderView('ZnaikaFrontendBundle:Search:search_parent_list.html.twig', array(
                'users' => $users,
            ));

            $array = array(
                'html'        => $html,
                'isFinalPage' => $isFinalPage,
                'countUsers'  => $countUsers,
            );

            return new JsonResponse($array);
        }

        public function searchChildAction()
        {
            return $this->render('ZnaikaFrontendBundle:Search:searchChild.html.twig', array());
        }

        public function searchChildAjaxAction(Request $request)
        {
            $searchString = trim($request->get("q", ""));
            $page         = intval($request->get("page"));
            $users        = $this->searchUsers($searchString, $request, UserRole::ROLE_USER, self::RESULT_ON_SPECIAL_PAGE, $page);
            $countUsers   = $this->countFoundedUsers($searchString, $request, UserRole::ROLE_USER);
            $isFinalPage  = $this->isFinalPage($countUsers, $page, self::RESULT_ON_SPECIAL_PAGE);

            $html = $this->renderView('ZnaikaFrontendBundle:Search:search_child_list.html.twig', array(
                'users' => $users,
            ));

            $array = array(
                'html'        => $html,
                'isFinalPage' => $isFinalPage,
                'countUsers'  => $countUsers,
            );

            return new JsonResponse($array);
        }

        public function searchAction(Request $request)
        {
            $searchString = trim($request->get("search_string"));

            $videos   = null;
            $chapters = array();
            if ($searchString)
            {
                $videos = $this->searchVideos($searchString, "", "");

                foreach ($videos as $video)
                {
                    $chapter                            = $video->getChapter();
                    $chapters[$chapter->getChapterId()] = $video->getChapter();
                }
            }

            $currentChapterId = null;
            if (!empty($chapters))
            {
                reset($chapters);
                $currentChapterId = key($chapters);
            }

            return $this->render('ZnaikaFrontendBundle:Search:search.html.twig', array(
                'searchString'     => $searchString,
                'videos'           => $videos,
                'chapters'         => $chapters,
                'currentChapterId' => $currentChapterId,
            ));
        }

        private function isFinalPage($count, $page, $itemsOnPage)
        {
            return $count < ($page + 1) * $itemsOnPage;
        }

        /**
         * @param $searchString
         * @param $subject
         * @param $grade
         * @param null $limit
         * @param null $page
         *
         * @return Video[]
         */
        private function searchVideos($searchString, $subject, $grade, $limit = null, $page = null)
        {
            $videoSearch = $this->getVideoSearch();
            $videos      = $videoSearch->getVideosBySearchString($searchString, $subject, $grade, $limit, $page);

            return $videos;
        }

        private function countFoundedVideos($searchString, $subject, $grade)
        {
            $videoSearch = $this->getVideoSearch();

            return $videoSearch->countVideosBySearchString($searchString, $subject, $grade);
        }

        /**
         * @return VideoSearch
         */
        private function getVideoSearch()
        {
            return $this->get("znaika.video_search");
        }

        /**
         * @return UserSearch
         */
        private function getUserSearch()
        {
            return $this->get("znaika.user_search");
        }

        private function searchUsers($searchString, Request $request, $role = null, $limit = null, $page = null)
        {
            $userSearch = $this->getUserSearch();
            $userSearch->setRole($role);
            $userSearch->initFromRequest($request);
            $users = $userSearch->getUsersBySearchString($searchString, $limit, $page);

            return $users;
        }

        private function countFoundedUsers($searchString, Request $request, $role = null)
        {
            $userSearch = $this->getUserSearch();
            $userSearch->setRole($role);
            $userSearch->initFromRequest($request);

            return $userSearch->countUserBySearchString($searchString);
        }


        /**
         * @param $searchString
         *
         * @return array
         */
        private function searchSynopsises($searchString)
        {
            $repository = $this->getSynopsisRepository();
            $synopsises = $repository->getSynopsisesBySearchString($searchString);

            return $synopsises;
        }

        /**
         * @return VideoRepository
         */
        private function getVideoRepository()
        {
            return $this->get("znaika.video_repository");
        }

        /**
         * @return UserRepository
         */
        private function getUserRepository()
        {
            return $this->get("znaika.user_repository");
        }

        /**
         * @return SynopsisRepository
         */
        private function getSynopsisRepository()
        {
            return $this->get("znaika.synopsis_repository");
        }

        /**
         * @return SubjectRepository
         */
        private function getSubjectRepository()
        {
            return $this->get("znaika.subject_repository");
        }

        /**
         * @param Request $request
         *
         * @return mixed
         */
        private function getSubjectIdFromRequest(Request $request)
        {
            $subjectName = $request->get("s", "");
            $repository  = $this->getSubjectRepository();

            $subject = $repository->getOneByUrlName($subjectName);

            return $subject ? $subject->getSubjectId() : 0;
        }
    }
