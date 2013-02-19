<?php

namespace INHack20\ConsultorioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\ConsultorioBundle\Entity\Medico;
use INHack20\ConsultorioBundle\Form\MedicoType;

/**
 * Medico controller.
 *
 * @Route("/medico")
 */
class MedicoController extends Controller
{
    /**
     * Lists all Medico entities.
     *
     * @Route("/", name="medico")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('INHack20ConsultorioBundle:Medico')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Medico entity.
     *
     * @Route("/{id}/show", name="medico_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('INHack20ConsultorioBundle:Medico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Medico entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Medico entity.
     *
     * @Route("/new", name="medico_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Medico();
        $form   = $this->createForm(new MedicoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Medico entity.
     *
     * @Route("/create", name="medico_create")
     * @Method("POST")
     * @Template("INHack20ConsultorioBundle:Medico:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Medico();
        $form = $this->createForm(new MedicoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('medico_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Medico entity.
     *
     * @Route("/{id}/edit", name="medico_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('INHack20ConsultorioBundle:Medico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Medico entity.');
        }

        $editForm = $this->createForm(new MedicoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Medico entity.
     *
     * @Route("/{id}/update", name="medico_update")
     * @Method("POST")
     * @Template("INHack20ConsultorioBundle:Medico:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('INHack20ConsultorioBundle:Medico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Medico entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MedicoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('medico_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Medico entity.
     *
     * @Route("/{id}/delete", name="medico_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('INHack20ConsultorioBundle:Medico')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Medico entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('medico'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
