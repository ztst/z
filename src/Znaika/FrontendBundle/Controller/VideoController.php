<?php

namespace Znaika\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VideoController extends Controller
{
    public function showCatalogueAction($class, $subjectName)
    {
        $subject = null;
        if( $subjectName )
        {
            $repository = $this->getDoctrine()
                               ->getRepository('ZnaikaFrontendBundle:Lesson\Category\Subject');
            $subject = $repository->findOneByUrlName($subjectName);
        }

        return $this->render('ZnaikaFrontendBundle:Video:showCatalogue.html.twig', array(
            'class'     => $class,
            'subject'   => $subject
        ));
    }

    public function showVideoAction($class, $subject, $id)
    {
        return $this->render('ZnaikaFrontendBundle:Video:showVideo.html.twig', array(
            'class'     => $class,
            'subject'   => $subject,
            'id'        => $id
        ));
    }

}
