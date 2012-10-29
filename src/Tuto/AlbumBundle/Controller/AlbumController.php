<?php

namespace Tuto\AlbumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tuto\AlbumBundle\Entity\Album;
use Tuto\AlbumBundle\Form\AlbumType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AlbumController extends Controller
{
    /**
     * @Route("/", name="_home")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $albums = $em->getRepository('TutoAlbumBundle:Album')->findAll();
        return array('albums' => $albums);
    }
    
    /**
     * @Route("/edit", name="edit")
     * @Template()
     */
    public function editAction(Request $request)
    {
        $em = $this->get('doctrine')->getEntityManager();
//         if (!empty($request->get("id"))) {
//             $album = $em->find('TutoAlbumBundle:Album', $request->get("id"));
//             $submitValue = "Edit";
//             $title = "Edit album";
//         } else {
            $album = new Album();
            $submitValue = "Add";
            $title = "Add new album";
//         }
        
        $form = $this->createForm(new AlbumType(), $album);
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em->persist($album);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', "Album saved successfully");
                return $this->redirect($this->generateUrl("_home"));
            }
        }
        
        return array(
            'form' => $form->createView(),
            'submitValue' => $submitValue,
            'title' => $title,
        );
    }
}
