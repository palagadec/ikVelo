<?php

namespace FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use FrontOfficeBundle\Entity\Deplacement;

/**
 * @Route("deplacements")
 */
class DeplacementController extends Controller
{
    /**
     * @Route("/list/{id}", name="front_deplacements_list")
     */
    public function listAction($id)
    {
        $user = $this->getDoctrine()->getRepository('FrontOfficeBundle:User')->find($id);
        $deplacements = $this->getDoctrine()->getRepository('FrontOfficeBundle:Deplacement')->findBy(array('user' => $user));
        return $this->render('FrontOfficeBundle:Deplacement:list.html.twig', array(
            'deplacements' => $deplacements
        ));
    }

    /**
     * @Route("/create/{id}", name="front_deplacements_create")
     */
    public function createAction(Request $request, $id)
    {   
        $deplacement = new Deplacement();
        $form = $this->createForm('FrontOfficeBundle\Form\DeplacementType', $deplacement);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getDoctrine()->getRepository('FrontOfficeBundle:User')->find($id);
            $deplacement->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($deplacement);
            $deplacement->setUpdated($deplacement->getCreated());
            $em->flush();

            return $this->redirectToRoute('front_deplacements_list', array('id' => $user->getId()));
        }

        return $this->render('FrontOfficeBundle:Deplacement:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/edit/{id}", name="front_deplacement_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Deplacement $deplacement)
    {
        $form = $this->createForm('FrontOfficeBundle\Form\DeplacementType', $deplacement);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $deplacement->setUpdated(time());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('front_deplacements_list', array('id' => $deplacement->getUser()->getId()));
        }

        return $this->render('FrontOfficeBundle:Deplacement:edit.html.twig', array(
            'deplacement' => $deplacement,
            'form' => $form->createView(),
        ));
    }

}
