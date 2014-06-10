<?php

namespace Cosa\FrontendBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{

    public function indexAction(Request $request)
    {

        $homepage = $this->container->get("cosa.frontend.manager")->getHomepage();

        return $this->render(
            'CosaFrontendBundle:Default:index.html.twig',
            array('homepage' => $homepage)
        );
    }

}
