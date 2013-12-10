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

        $videoRepository = $this->getDoctrine()->getRepository('ZnaikaFrontendBundle:Lesson\Content\Video');
        $videos = $videoRepository->getVideosForCatalog($class, $subjectName);

        return $this->render('ZnaikaFrontendBundle:Video:showCatalogue.html.twig', array(
            'isValidClass' => $isValidClass,
            'class'        => $class,
            'subject'      => $subject,
            'videos'       => $videos
        ));
    }

    public function showVideoAction($class, $subjectName, $videoName)
    {
        $subject = null;
        $video = null;
        if( $videoName )
        {
            $repository = $this->getDoctrine()
                               ->getRepository('ZnaikaFrontendBundle:Lesson\Content\Video');
            $video = $repository->findOneByUrlName($videoName);
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
            $repository = $this->getDoctrine()
                               ->getRepository('ZnaikaFrontendBundle:Lesson\Category\Subject');
            $subject = $repository->findOneByUrlName($subjectName);
        }
        return $subject;
    }
}
