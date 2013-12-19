<?php

namespace Znaika\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function searchAction(Request $request)
    {
        $videos = $this->searchVideos($request->get("search_string"));

        return $this->render('ZnaikaFrontendBundle:Search:search.html.twig', array(
            'videos' => $videos
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
}
