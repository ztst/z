<?php

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;

    class SearchController extends Controller
    {
        const SIMILAR_VIDEOS_LIMIT     = 10;
        const MAX_VIDEO_SEARCH_RESULTS = 20;

        public function searchSimilarVideoAction(Request $request)
        {
            $searchString = $request->get("similar_video_name_string");
            $videoName    = $request->get("videoName");
            $videos       = null;
            if ($searchString)
            {
                /** @var VideoRepository $repository */
                $repository   = $this->get("znaika_frontend.video_repository");
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

        public function searchAction(Request $request)
        {
            $searchString = $request->get("search_string");
            $videos       = null;
            $users        = null;
            $synopsises   = null;
            if ($searchString)
            {
                $videos     = $this->searchVideos($searchString, self::MAX_VIDEO_SEARCH_RESULTS);
                $users      = $this->searchUsers($searchString);
                $synopsises = $this->searchSynopsises($searchString);
            }

            return $this->render('ZnaikaFrontendBundle:Search:search.html.twig', array(
                'videos'     => $videos,
                'users'      => $users,
                'synopsises' => $synopsises,
            ));
        }

        /**
         * @param $searchString
         * @param $limit
         *
         * @return array
         */
        private function searchVideos($searchString, $limit = null)
        {
            /** @var VideoRepository $repository */
            $repository = $this->get("znaika_frontend.video_repository");
            $videos     = $repository->getVideosBySearchString($searchString, $limit);

            return $videos;
        }

        /**
         * @param $searchString
         *
         * @return array
         */
        private function searchUsers($searchString)
        {
            $repository = $this->get("znaika_frontend.user_repository");
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
            $repository = $this->get("znaika_frontend.synopsis_repository");
            $synopsises = $repository->getSynopsisesBySearchString($searchString);

            return $synopsises;
        }
    }
