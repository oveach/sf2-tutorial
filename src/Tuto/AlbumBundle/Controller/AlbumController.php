<?php

namespace Tuto\AlbumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
// these import the "@Route" and "@Template" annotations
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tuto\AlbumBundle\Entity\Album;
use Tuto\AlbumBundle\Form\AlbumType;


class AlbumController extends Controller
{
    /**
     * _Route("/album", name="_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $albums = $em->getRepository('TutoAlbumBundle:Album')->findAll();
        return array('albums' => $albums);
    }
    
    /**
     * _Route("/album/edit/{id}", requirements={"id" = "\d+"}, defaults={"id" = null}, name="_edit")
     * @Template()
     */
    public function editAction($id, Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        if (!empty($id)) {
            $album = $em->find('TutoAlbumBundle:Album', $id);
            if (!$album instanceof Album) {
                throw new \Exception("Album with id $id not found");
            }
            $submitValue = "Edit";
            $title = "Edit album";
        } else {
            $album = new Album();
            $submitValue = "Add";
            $title = "Add new album";
        }
        
        $form = $this->createForm(new AlbumType(), $album);
        
        $form->handleRequest($request);   // determine automatically if form is submitted
        if ($form->isValid()) {           // false if not submitted
            $em->persist($album);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', "Album saved successfully");
            return $this->redirect($this->generateUrl("_index"));
        }
        
        return array(
            'form' => $form->createView(),
            'id' => $id,
            'submitValue' => $submitValue,
            'title' => $title,
        );
    }

    /**
     * _Route("/album/delete/{id}", requirements={"id" = "\d+"}, name="_delete")
     * @Template()
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $album = $em->find('TutoAlbumBundle:Album', $id);
        if (!$album instanceof Album) {
            throw new \Exception("Album with id $id not found");
        }
        
        if ($request->getMethod() == 'POST') {
            if ($request->request->has('confirm')) {
                $em->remove($album);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', "Album deleted successfully");
            }
            return $this->redirect($this->generateUrl("_index"));
        } else {
            return array(
                'album' => $album,
            );
        }
    }

    /**
     * Construct variables to display in footer
     * @param Request $request
     * @Template()
     */
    public function footerAction(Request $request)
    {
        return array(
            'phpVersion' => PHP_VERSION,
            'symfonyVersion' => \Symfony\Component\HttpKernel\Kernel::VERSION,
            'doctrineVersion' => \Doctrine\ORM\Version::VERSION,
        );
    }
}
