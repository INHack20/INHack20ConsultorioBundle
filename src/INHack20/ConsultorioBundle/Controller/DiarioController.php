<?php

namespace INHack20\ConsultorioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\ConsultorioBundle\Entity\Diario;
use INHack20\ConsultorioBundle\Form\DiarioType;

/**
 * Diario controller.
 *
 * @Route("/diario")
 */
class DiarioController extends Controller
{
    /**
     * Lists all Diario entities.
     *
     * @Route("/", name="diario")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('INHack20ConsultorioBundle:Diario')->findAll();
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Diario entity.
     *
     * @Route("/{id}/show", name="diario_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('INHack20ConsultorioBundle:Diario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Diario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Diario entity.
     *
     * @Route("/new", name="diario_new")
     * @Template()
     */
    public function newAction()
    {
        //$mypDF = $this->container->get('white_october.tcpdf')->create();
        $entity = new Diario();
        $form   = $this->createForm(new DiarioType(), $entity);
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Diario entity.
     *
     * @Route("/create", name="diario_create")
     * @Method("POST")
     * @Template("INHack20ConsultorioBundle:Diario:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Diario();
        $form = $this->createForm(new DiarioType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('mensaje','2');
            $this->get('session')->setFlash('tipo','1');
            
            return $this->redirect($this->generateUrl('diario_show', array('id' => $entity->getId())));
        }
        
         $this->get('session')->setFlash('mensaje','5');
         $this->get('session')->setFlash('tipo','2');
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Diario entity.
     *
     * @Route("/{id}/edit", name="diario_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('INHack20ConsultorioBundle:Diario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Diario entity.');
        }

        $editForm = $this->createForm(new DiarioType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Diario entity.
     *
     * @Route("/{id}/update", name="diario_update")
     * @Method("POST")
     * @Template("INHack20ConsultorioBundle:Diario:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('INHack20ConsultorioBundle:Diario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Diario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DiarioType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->setFlash('mensaje','3');
            $this->get('session')->setFlash('tipo','1');
            
            return $this->redirect($this->generateUrl('diario_edit', array('id' => $id)));
        }
        
        $this->get('session')->setFlash('mensaje','6');
        $this->get('session')->setFlash('tipo','2');
         
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Diario entity.
     *
     * @Route("/{id}/delete", name="diario_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('INHack20ConsultorioBundle:Diario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Diario entity.');
            }

            $this->get('session')->setFlash('mensaje','7');
            $this->get('session')->setFlash('tipo','1');
            
            $em->remove($entity);
            try {
                $em->flush();
            } catch (\Doctrine\DBAL\DBALException $exc) {
                $this->get('session')->setFlash('mensaje','8');
                $this->get('session')->setFlash('tipo','3');
            }
        }
        return $this->redirect($this->generateUrl('diario'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Recuperar los diarios registrados en el dia 
     * @Route("/findToday", name="diario_today")     
     * @Template("INHack20ConsultorioBundle:Diario:index.html.twig")
     */
    public function findTodayAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('INHack20ConsultorioBundle:Diario')->findToday();
        if(count($entity) == 0){
            $this->get('session')->setFlash('mensaje','9');
            $this->get('session')->setFlash('tipo','2');
            }
        return array(
            'entities' => $entity,
        );
    }
    
    /**
     * Permite buscar los diarios de acuerdo a algunos criterios
     * @Route("/search", name="diario_search") 
     * @Template()
     */
    public function searchAction(){
        return array();
    }
}
