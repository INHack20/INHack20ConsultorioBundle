<?php

namespace INHack20\ConsultorioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\ConsultorioBundle\Entity\Paciente;
use INHack20\ConsultorioBundle\Form\PacienteType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Paciente controller.
 *
 * @Route("/paciente")
 */
class PacienteController extends Controller
{
    /**
     * Lists all Paciente entities.
     *
     * @Route("/", name="paciente")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('INHack20ConsultorioBundle:Paciente')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Paciente entity.
     *
     * @Route("/{id}/show", name="paciente_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('INHack20ConsultorioBundle:Paciente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paciente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Paciente entity.
     *
     * @Route("/new", name="paciente_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Paciente();
        $form   = $this->createForm(new PacienteType(), $entity);
        
        $em = $this->getDoctrine()->getEntityManager();
        $fecha = new \DateTime();
        $qb = $em->createQueryBuilder('d');
        
        $qb->select('d')->from('INHack20ConsultorioBundle:Diario', 'd');
        $qb->where($qb->expr()->like('d.fechaCreado', "'".$fecha->format("Y-m-d")."%'"));
        
        
        $diarios = $qb->getQuery()->getResult();
        
        if(count($diarios) == 0)
        {
            $this->get('session')->setFlash('mensaje','1');
            $this->get('session')->setFlash('tipo','2');
            return $this->redirect($this->generateUrl('diario_new'));
        };
        
        $entity->setDiario($diarios[0]);
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Paciente entity.
     *
     * @Route("/create", name="paciente_create")
     * @Method("POST")
     * @Template("INHack20ConsultorioBundle:Paciente:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Paciente();
        $form = $this->createForm(new PacienteType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->setFlash('tipo','1');
            $this->get('session')->setFlash('mensaje','2');
            
            return $this->redirect($this->generateUrl('paciente_show', array('id' => $entity->getId())));
        }

        $this->get('session')->setFlash('tipo','2');
        $this->get('session')->setFlash('mensaje','5');
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Paciente entity.
     *
     * @Route("/{id}/edit", name="paciente_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('INHack20ConsultorioBundle:Paciente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paciente entity.');
        }

        $editForm = $this->createForm(new PacienteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Paciente entity.
     *
     * @Route("/{id}/update", name="paciente_update")
     * @Method("POST")
     * @Template("INHack20ConsultorioBundle:Paciente:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('INHack20ConsultorioBundle:Paciente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paciente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PacienteType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->setFlash('tipo','1');
            $this->get('session')->setFlash('mensaje','3');
            
            return $this->redirect($this->generateUrl('paciente_edit', array('id' => $id)));
        }
        
        $this->get('session')->setFlash('tipo','2');
        $this->get('session')->setFlash('mensaje','5');
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Paciente entity.
     *
     * @Route("/{id}/delete", name="paciente_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('INHack20ConsultorioBundle:Paciente')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Paciente entity.');
            }

            $em->remove($entity);
            $em->flush();
            
            $this->get('session')->setFlash('tipo','1');
            $this->get('session')->setFlash('mensaje','7');
        }
        
        return $this->redirect($this->generateUrl('paciente'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Devuelve los pacientes atendidos en el dia actual
     * @Route("/findToday",name="paciente_findtoday")
     * @Template("INHack20ConsultorioBundle:Paciente:index.html.twig")
     * @return type 
     */
    public function findTodayAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('INHack20ConsultorioBundle:Paciente')->findToday();
        if(count($entity) == 0)
        {
            $this->get('session')->setFlash('tipo','2');
            $this->get('session')->setFlash('mensaje','10');
        }
        
        return array(
            'entities' => $entity,
        );
    }
}