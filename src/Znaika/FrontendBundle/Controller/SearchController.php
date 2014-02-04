<?php

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Repository\Lesson\Content\SynopsisRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class SearchController extends Controller
    {
        const SIMILAR_VIDEOS_LIMIT   = 10;
        const RESULTS_ON_ALL_SEARCH  = 2;
        const RESULT_ON_SPECIAL_PAGE = 15;

        public function searchSimilarVideoAction(Request $request)
        {
            $searchString = $request->get("similar_video_name_string");
            $videoName    = $request->get("videoName");
            $videos       = null;
            if ($searchString)
            {
                $repository = $this->getVideoRepository();
                $currentVideo = $repository->getOneByUrlName($videoName);
                $videos       = $repository->getNotSimilarVideosBySearchString($currentVideo, $searchString,
                    self::SIMILAR_VIDEOS_LIMIT);
            }

            return $this->render('ZnaikaFrontendBundle:Search:searchSimilarVideo.html.twig', array(
                'videos'       => $videos,
                'videoName'    => $videoName,
                'searchString' => $searchString,
            ));
        }

        public function searchVideoAction(Request $request)
        {
            $searchString = $request->get("searchString");
            $videos       = $this->searchVideos($searchString, self::RESULT_ON_SPECIAL_PAGE);
            $countVideos  = $this->countFoundedVideos($searchString);
            $isFinalPage  = $this->isFinalPage($countVideos, 0, self::RESULT_ON_SPECIAL_PAGE);

            return $this->render('ZnaikaFrontendBundle:Search:searchVideo.html.twig', array(
                'videos'       => $videos,
                'searchString' => $searchString,
                'isFinalPage'  => $isFinalPage
            ));
        }

        public function searchVideoAjaxAction(Request $request)
        {
            $searchString = $request->get("searchString");
            $page         = intval($request->get("page"));
            $videos       = $this->searchVideos($searchString, self::RESULT_ON_SPECIAL_PAGE, $page);
            $countVideos  = $this->countFoundedVideos($searchString);
            $isFinalPage  = $this->isFinalPage($countVideos, $page, self::RESULT_ON_SPECIAL_PAGE);

            $html = $this->renderView('ZnaikaFrontendBundle:Search:search_video_list.html.twig', array(
                'videos' => $videos
            ));

            $array   = array(
                'html'        => $html,
                'isFinalPage' => $isFinalPage
            );

            return new JsonResponse($array);
        }

        public function searchAction(Request $request)
        {
            $searchString = $request->get("search_string");

            $videos      = null;
            $countVideos = 0;
            $users       = null;
            $synopsises  = null;
            if ($searchString)
            {
                $videos      = $this->searchVideos($searchString, self::RESULTS_ON_ALL_SEARCH);
                $countVideos = $this->countFoundedVideos($searchString);
                $users       = $this->searchUsers($searchString);
                $synopsises  = $this->searchSynopsises($searchString);
            }

            return $this->render('ZnaikaFrontendBundle:Search:search.html.twig', array(
                'searchString' => $searchString,
                'videos'       => $videos,
                'countVideos'  => $countVideos,
                'users'        => $users,
                'synopsises'   => $synopsises,
            ));
        }

        private function isFinalPage($count, $page, $itemsOnPage)
        {
            return $count < ($page + 1) * $itemsOnPage;
        }

        /**
         * @param $searchString
         * @param $limit
         * @param $page
         *
         * @return array
         */
        private function searchVideos($searchString, $limit = null, $page = null)
        {
            $repository = $this->getVideoRepository();
            $videos     = $repository->getVideosBySearchString($searchString, $limit, $page);

            return $videos;
        }

        /**
         * @param $searchString
         *
         * @return array
         */
        private function searchUsers($searchString)
        {
            $repository = $this->getUserRepository();
            $users      = $repository->getUsersBySearchString($searchString);

            return $users;
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

        private function countFoundedVideos($searchString)
        {
            $repository  = $this->getVideoRepository();
            $countVideos = $repository->countVideosBySearchString($searchString);

            return $countVideos;
        }

        /**
         * @return VideoRepository
         */
        private function getVideoRepository()
        {
            return $this->get("znaika_frontend.video_repository");
        }

        /**
         * @return UserRepository
         */
        private function getUserRepository()
        {
            return $this->get("znaika_frontend.user_repository");
        }

        /**
         * @return SynopsisRepository
         */
        private function getSynopsisRepository()
        {
            return $this->get("znaika_frontend.synopsis_repository");
        }
    }
