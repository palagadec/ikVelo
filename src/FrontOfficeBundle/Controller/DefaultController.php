<?php

namespace FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FrontOfficeBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction()
    {
        return $this->render('FrontOfficeBundle:Default:index.html.twig');
    }

    /**
     * @Route("/")
     */
    public function landingAction($id = 2)
    {
        if (is_numeric($id)) {
            $user = $this->getDoctrine()->getRepository('FrontOfficeBundle:User')->find($id);

            
            return $this->render('FrontOfficeBundle:User:landing.html.twig', array(
                'userFirstname' => $user->getPrenom(),
                'userLastname' => $user->getNom(),
            ));
        } else {
            throw new BadRequestHttpException('Invalid URL', null, 400);
        }
    }
}
