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
    
    /**
     * Permite buscar los pacientes de acuerdo a algunos criterios
     * @Route("/consult", name="paciente_consult") 
     * @Template()
     */
    public function consultAction(){
        $request = $this->getRequest();
        $options = array(
                        'diagnostico' => 'Diagnostico',
                        'tratamiento' => 'Tratamiento',
                        'cedula' => 'Cedula',
                        'nombreCompleto' => 'Nombre Completo',
            );
        $form = $this->createForm(new ConsultType($options));  
    
        if($request->getMethod() == 'POST' && $request->isXmlHttpRequest()){
            $class = "INHack20ConsultorioBundle:Paciente";
            $view = 'INHack20ConsultorioBundle:Paciente:index_content.html.twig';
            
            return $this->getSearchResult($options, $class, $view);
            
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
        
        // set document information
        $pdf->SetCreator('Symfony2 PDF');
        $pdf->SetAuthor('Barrio Adentro I');
        $pdf->SetTitle('Reporte');
        $pdf->SetSubject('Barrio Adentro I');
        $pdf->SetKeywords('Barrio Adentro');
        $pdf->setTranslator($translator);
        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        $pdf->AddPage();
        
$html = '
    <table style="width: 100%;" border="0" cellpadding="2" cellspacing="2">
            <tbody>
                <tr>
                    <td>'.$translator->trans('title.estado',array(),'pdf').':estado</td>
                    <td>'.$translator->trans('title.nombreMedico',array(),'pdf').':nombreMedico</td>
                    <td>'.$translator->trans('title.fecha',array(),'pdf').':fecha</td>
                </tr>
                <tr>
                    <td>'.$translator->trans('title.municipio',array(),'pdf').':municipio</td>
                    <td>'.$translator->trans('title.asic',array(),'pdf').':asic</td>
                    <td>'.$translator->trans('title.consultorio',array(),'pdf').':consultorio</td>
                </tr>
            </tbody>
    </table>
';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '
    <table style="width: 100%; text-align:center; " border="1" cellpadding="0" cellspacing="0">
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
        </tr>
        <tr>
            <td>1</td>
            <td>19108122</td>
            <td>Carlos Mendoza</td>
            <td>E</td>
            <td>S</td>
            <td>DIRECCIÓN</td>
            <td>CN</td>
            <td>DAGNÓSTICODAGNÓSTICODAGNÓSTICODAGNÓSTICO</td>
            <td>TRATAMIENTO</td>
        </tr>
    </table>
';
$pdf->SetFont('helvetica', '', 9);
$pdf->writeHTML($html, true, false, true, false, '');
    
    return new \Symfony\Component\HttpFoundation\Response($pdf->Output('example_006.pdf', 'I'),
                200,array('Content-Type' => 'application/pdf'));
    }
    
    /**
     * 
     * @param array() $options
     * @param string $class
     * @param string $view
     * @return viewHtml
     */
    private function getSearchResult($options,$class,$view,$returnEntity = false,$data = NULL ){
        $request = $this->getRequest();
        
        $form = $this->createForm(new ConsultType($options));  
        $entities = NULL;
        
            $form->bindRequest($request);
            if($form->isValid() || $data){
                if(!$data){
                    $data = $form->getData();
                    }
                    else{
                        $data = $data['data'];
                    }
                $medico = NULL;
                $busqueda = NULL;
                $criterio = NULL;
                $fechaDesde = NULL;
                $fechaHasta = NULL;
                if(isset($data['medico'])){
                    $medico = $data['medico'];
                }
                if(isset($data['busqueda'])){
                    $busqueda = $data['busqueda'];
                }
                if(isset($data['criterio'])){
                    $criterio = $data['criterio'];
                }
                if($data['fechaDesde']){
                    $fechaDesde = $data['fechaDesde'];
                    if(is_object($fechaDesde))
                         $fechaDesde = $fechaDesde->format('Y-m-d');
                    $data['fechaDesde'] = $fechaDesde;
                }
                if(isset($data['fechaHasta'])){
                    $fechaHasta = $data['fechaHasta'];
                    if(is_object($fechaHasta))
                        $fechaHasta = $fechaHasta->format('Y-m-d');
                    $data['fechaHasta'] = $fechaHasta;
                }
                
                $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
                $qb->select('d')->from($class, 'd');
                    
                    if($busqueda && $criterio){
                        $qb->andWhere($qb->expr()->like('d.'.$busqueda,"'%".$criterio."%'"));
                    }
                    if($fechaDesde && !$fechaHasta){
                        $qb->andWhere($qb->expr()->like('d.fechaCreado',"'".$fechaDesde."%'"));
                    }
                    if($fechaDesde && $fechaHasta){
                        $qb->andWhere('d.fechaCreado >= :fechaCreado');
                        $qb->andWhere('d.fechaCreado <= :fechaCreado2');
                        $qb->setParameters(new \Doctrine\Common\Collections\ArrayCollection(array(
                            new \Doctrine\ORM\Query\Parameter('fechaCreado',$fechaDesde),
                            new \Doctrine\ORM\Query\Parameter('fechaCreado2',$fechaHasta.' 23:59:59'),
                        )));
                    }
                   //echo $qb->getQuery()->getSQL();die;
                    $entities = $qb->getQuery()->getResult();
            }
            if($returnEntity){
                return $entities;
            }
            else{
                return $this->render($view,array(
                    'entities' => $entities,
                    'data' => $data,
                ));
            }
    }
}