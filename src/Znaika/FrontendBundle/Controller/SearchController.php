<?php

namespace Znaika\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function searchAction(Request $request)
    {
        $searchString = $request->get("search_string");
        $videos = $this->searchVideos($searchString);
        $users = $this->searchUsers($searchString);

        return $this->render('ZnaikaFrontendBundle:Search:search.html.twig', array(
            'videos' => $videos,
            'users'  => $users,
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
        $users     = $repository->getUsersBySearchString($searchString);

        return $users;
    }
}
