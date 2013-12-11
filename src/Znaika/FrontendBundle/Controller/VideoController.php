<?php

namespace Znaika\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class VideoController extends Controller
{
    public function getVideosAction(Request $request)
    {
        $subjectName = $request->request->get("subject");
        $class = $request->request->get("class");

        $videoRepository = $this->get("video_repository");
        $videos = $videoRepository->getVideosForCatalog($class, $subjectName);

        $response = new JsonResponse();
        $content = $this->renderView('ZnaikaFrontendBundle:Video:video-catalog.html.twig', array(
            'videos' => $videos
        ));

        $response->setData(array(
            'content' => $content
        ));

        return $response;
    }

    public function showCatalogueAction($class, $subjectName)
    {
        $subjectRepository = $this->get("subject_repository");
        $subjects = $subjectRepository->getAll();
        $classes = ClassNumberUtil::getAvailableClasses();

        $videoRepository = $this->get("video_repository");
        $videos = $videoRepository->getVideosForCatalog($class, $subjectName);

        return $this->render('ZnaikaFrontendBundle:Video:showCatalogue.html.twig', array(
            'classes'            => $classes,
            'currentClass'       => $class,
            'currentSubjectName' => $subjectName,
            'subjects'           => $subjects,
            'videos'             => $videos,
        ));
    }

    public function showVideoAction($class, $subjectName, $videoName)
    {
        $repository = $this->get("video_repository");
        $video = $repository->getOneByUrlName($videoName);

        $subject = null;
        if( $video )
        {
            $subject = $video->getSubject();
        }

        $isValidUrl = !is_null($video) && $subject->getUrlName() == $subjectName && $video->getGrade() == $class;

        return $this->render('ZnaikaFrontendBundle:Video:showVideo.html.twig', array(
            'classNumber'   => $class,
            'subject'       => $subject,
            'video'         => $video,
            'isValidUrl'    => $isValidUrl
        ));
    }

    /**
     * @param $subjectName
     *
     * @return null
     */
    protected function getSubjectByName($subjectName)
    {
        $subject = null;
        if( $subjectName )
        {
            $repository = $this->get('subject_repository');
            $subject = $repository->getOneByUrlName($subjectName);
        }
        return $subject;
    }
}
