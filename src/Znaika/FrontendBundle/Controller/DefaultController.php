<?php

namespace Znaika\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ZnaikaFrontendBundle:Default:index.html.twig');
    }
}
