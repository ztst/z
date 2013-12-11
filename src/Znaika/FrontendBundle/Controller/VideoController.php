<?php

namespace Znaika\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;

class VideoController extends Controller
{
    public function showCatalogueAction($class, $subjectName)
    {
        $isValidClass = ClassNumberUtil::isValidClassNumber($class);
        $subject = $this->getSubjectByName($subjectName);

        $repository = $this->get("video_repository");
        $videos = $repository->getVideosForCatalog($class, $subjectName);

        return $this->render('ZnaikaFrontendBundle:Video:showCatalogue.html.twig', array(
            'isValidClass' => $isValidClass,
            'class'        => $class,
            'subject'      => $subject,
            'videos'       => $videos
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
