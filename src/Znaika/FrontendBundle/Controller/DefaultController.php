<?php

namespace Znaika\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()
                           ->getRepository('ZnaikaFrontendBundle:Lesson\Category\Subject');
        $subjects = $repository->findAll();

        $classes = ClassNumberUtil::getAvailableClasses();

        return $this->render('ZnaikaFrontendBundle:Default:index.html.twig', array(
            "subjects"  => $subjects,
            "classes"   => $classes
        ));
    }
}
