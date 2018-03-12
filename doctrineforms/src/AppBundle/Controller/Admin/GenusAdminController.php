<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Form\GenusFormType;
use http\Env\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin")
 */
class GenusAdminController extends Controller
{
    /**
     * @Route("/genus", name="admin_genus_list")
     */
    public function indexAction()
    {
        $genuses = $this->getDoctrine()
            ->getRepository('AppBundle:Genus')
            ->findAll();

        return $this->render('admin/genus/list.html.twig', array(
            'genuses' => $genuses
        ));
    }

    /**
     * @Route("/genus/new", name="admin_genus_new")
     */
    public function newAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $form = $this->createForm(GenusFormType::class);

        // only handle data on POST request
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dump($form->getData()); die;
        }

        return $this->render('admin/genus/new.html.twig', [
            'genusForm' => $form->createView()
        ]);
    }
}