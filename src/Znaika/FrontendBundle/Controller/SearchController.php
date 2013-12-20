<?php

namespace Znaika\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function searchAction(Request $request)
    {
        $searchString = $request->get("search_string");
        $videos     = $this->searchVideos($searchString);
        $users      = $this->searchUsers($searchString);
        $synopsises = $this->searchSynopsises($searchString);

        return $this->render('ZnaikaFrontendBundle:Search:search.html.twig', array(
            'videos'     => $videos,
            'users'      => $users,
            'synopsises' => $synopsises,
        ));
    }

    /**
     * @param $searchString
     *
     * @return array
     */
    private function searchVideos($searchString)
    {
        $repository = $this->get("video_repository");
        $videos     = $repository->getVideosBySearchString($searchString);

        return $videos;
    }

    /**
     * @param $searchString
     *
     * @return array
     */
    private function searchUsers($searchString)
    {
        $repository = $this->get("user_repository");
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
        $repository = $this->get("synopsis_repository");
        $synopsises = $repository->getSynopsisesBySearchString($searchString);

        return $synopsises;
    }
}
