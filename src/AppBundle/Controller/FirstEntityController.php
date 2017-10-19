<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FirstEntity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ProductType;

/**
 * Firstentity controller.
 *
 * @Route("firstentity")
 */
class FirstEntityController extends Controller
{
    /**
     * Lists all firstEntity entities.
     *
     * @Route("/", name="firstentity_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $firstEntities = $em->getRepository('AppBundle:FirstEntity')->findAll();

        return $this->render('firstentity/index.html.twig', array(
            'firstEntities' => $firstEntities,
        ));
    }

    /**
     * Creates a new firstEntity entity.
     *
     * @Route("/new", name="firstentity_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $firstEntity = new Firstentity();
        $form = $this->createForm('AppBundle\Form\FirstEntityType', $firstEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file contient l'image nouvellement uploadée
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $firstEntity->getFile();
            
            // Génération d'un nom unique pour l'image (pour éviter les collisions à l'enregistrement)
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            
            // Déplacement l'image dans le dossier
                $file->move(
                    $firstEntity->getCoverUploadDirectory(),
                    $fileName);
            $firstEntity->setCover($fileName);        
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($firstEntity);
            $em->flush();

            //return $this->redirect($this->generateUrl('app_article_list'));
            return $this->redirectToRoute('firstentity_show', array('id' => $firstEntity->getId()));
        }

        return $this->render('firstentity/new.html.twig', array(
            'firstEntity' => $firstEntity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a firstEntity entity.
     *
     * @Route("/{id}", name="firstentity_show")
     * @Method("GET")
     */
    public function showAction(FirstEntity $firstEntity)
    {
        $deleteForm = $this->createDeleteForm($firstEntity);

        return $this->render('firstentity/show.html.twig', array(
            'firstEntity' => $firstEntity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing firstEntity entity.
     *
     * @Route("/{id}/edit", name="firstentity_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FirstEntity $firstEntity)
    {
        $deleteForm = $this->createDeleteForm($firstEntity);
        $editForm = $this->createForm('AppBundle\Form\FirstEntityType', $firstEntity);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('firstentity_edit', array('id' => $firstEntity->getId()));
        }

        return $this->render('firstentity/edit.html.twig', array(
            'firstEntity' => $firstEntity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a firstEntity entity.
     *
     * @Route("/{id}", name="firstentity_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FirstEntity $firstEntity)
    {
        $form = $this->createDeleteForm($firstEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($firstEntity);
            $em->flush();
        }

        return $this->redirectToRoute('firstentity_index');
    }

    /**
     * Creates a form to delete a firstEntity entity.
     *
     * @param FirstEntity $firstEntity The firstEntity entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FirstEntity $firstEntity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('firstentity_delete', array('id' => $firstEntity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
