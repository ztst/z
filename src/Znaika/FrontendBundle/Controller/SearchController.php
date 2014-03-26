<?php

    namespace Znaika\FrontendBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Repository\Lesson\Content\SynopsisRepository;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class SearchController extends ZnaikaController
    {
        const RESULT_ON_SPECIAL_PAGE = 15;

        public function searchVideoAction(Request $request)
        {
            $searchString = $request->get("q", "");
            $subject      = $request->get("s", "");
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
            $searchString = $request->get("q", "");
            $subject      = $request->get("s", "");
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

        public function searchAction(Request $request)
        {
            $searchString = $request->get("search_string");

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
            $repository = $this->getVideoRepository();
            $videos     = $repository->getVideosBySearchString($searchString, $subject, $grade, $limit, $page);

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

        private function countFoundedVideos($searchString, $subject, $grade)
        {
            $repository  = $this->getVideoRepository();
            $countVideos = $repository->countVideosBySearchString($searchString, $subject, $grade);

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
