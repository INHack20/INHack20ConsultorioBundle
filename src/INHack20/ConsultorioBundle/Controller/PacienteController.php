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
use INHack20\ConsultorioBundle\Form\ConsultType;
use INHack20\ConsultorioBundle\Model\Search;

/**
 * Paciente controller.
 *
 * @Route("/paciente")
 */
class PacienteController extends Controller
{
   private $options = array(
                        'diagnostico' => 'Diagnostico',
                        'tratamiento' => 'Tratamiento',
                        'cedula' => 'Cedula',
                        'nombreCompleto' => 'Nombre Completo',
            );
   
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
    
    /**
     * Permite buscar los pacientes de acuerdo a algunos criterios
     * @Route("/consult", name="paciente_consult") 
     * @Template()
     */
    public function consultAction(){
        $request = $this->getRequest();
        
        $form = $this->createForm(new ConsultType($this->options,true));  
    
        if($request->getMethod() == 'POST' && $request->isXmlHttpRequest()){
            $class = "INHack20ConsultorioBundle:Paciente";
            $view = 'INHack20ConsultorioBundle:Paciente:index_content.html.twig';
            $form->bindRequest($request);
            $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
            $param = Search::getSearchResult($this->options, $class, $view, $qb, false, $form->getData());
            return $this->render($view,$param);
        }
        
        return array(
            'form' => $form->createView(),
        );
    }
    
    
    /**
     * Exporta los resultados de un filtro a un archivo pdf
     * @Route("/exportPDF", name="paciente_exportPDF")
     */
    public function exportPDFAction(){
        
        $pdf= $this->get('white_october.tcpdf')->create();
        $translator = $this->get('translator');
        
        $data = $this->getRequest()->query->all();
        $class = "INHack20ConsultorioBundle:Paciente";
        $view = 'INHack20ConsultorioBundle:Paciente:index_content.html.twig';
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        $entities = Search::getSearchResult($this->options, $class, $view, $qb, true, $data);
        
        $baseRoot = $this->getRequest()->server->get('DOCUMENT_ROOT');
        $baseRoot[strlen($baseRoot) - 1 ] = NULL;
        $baseRoot = trim($baseRoot);
        $logo = $baseRoot . $this->get('templating.helper.assets')->getUrl('bundles/inhack20consultorio/images/logo_barrio.jpg');
        $logo2 = $baseRoot . $this->get('templating.helper.assets')->getUrl('bundles/inhack20consultorio/images/logo_sistema.jpg');
        
        // set document information
        $pdf->SetCreator('Symfony2 PDF');
        $pdf->SetAuthor('Barrio Adentro I');
        $pdf->SetTitle('Reporte');
        $pdf->SetSubject('Barrio Adentro I');
        $pdf->SetKeywords('Barrio Adentro');
        $pdf->setTranslator($translator);
        $pdf->setTitulo($translator->trans('header.4',array(),'pdf'));
        $pdf->setLogo($logo);
        $pdf->setLogo2($logo2);
        $pdf->setResume(true);
        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 25, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        $pdf->AddPage();
        
$html = '
    <table style="width: 100%; text-align:center; " border="1" cellpadding="0" cellspacing="0">';

$html.='
        <tr>
            <th style="width: 4%;">Nº</th>
            <th style="width: 8%;">CEDULA</th>
            <th style="width: 17%;">NOMBRES Y APELLIDOS</th>
            <th style="width: 4%;">E</th>
            <th style="width: 4%;">S</th>
            <th style="width: 20%;">DIRECCIÓN</th>
            <th style="width: 4%;">T</th>
            <th style="width: 20%;">DAGNÓSTICO</th>
            <th style="width: 20%;">TRATAMIENTO</th>
        </tr>';
if($entities){
   $i = 0;
   foreach ($entities as $entity){
   $html.='
        <tr>
            <td>'.($i + 1).'</td>
            <td>'.$entity->getCedula().'</td>
            <td>'.$entity->getNombreCompleto().'</td>
            <td>'.$entity->getEdad().'</td>
            <td>'.$entity->getSexo().'</td>
            <td>'.$entity->getDireccion().'</td>
            <td>'.$entity->getTipoConsulta()->getAcronimo().'</td>
            <td>'.$entity->getDiagnostico().'</td>
            <td>'.$entity->getTratamiento().'</td>
        </tr>';
        $i++;
        $pdf->setTotal($i);
   }//fin for
}
$html.='
    </table>
';
$pdf->SetFont('helvetica', '', 9);
$pdf->writeHTML($html, true, false, true, false, '');
    
    return new \Symfony\Component\HttpFoundation\Response($pdf->Output('example_006.pdf', 'I'),
                200,array('Content-Type' => 'application/pdf'));
    }
    
    
}