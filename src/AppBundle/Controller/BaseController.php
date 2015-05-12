<?php

namespace AppBundle\Controller;

/**
 * Description of BaseController
 *
 * @author Jonathan Claros <jclaros at lysoftbo.com>
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BaseController extends Controller{
  
  /**
     * @Route("/", name="initial")
     */
  public function homepageAction()
    {
        return $this->render(
            'home.html.twig'
        );
    }
}
