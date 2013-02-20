<?php

namespace INHack20\ConsultorioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\ConsultorioBundle\Entity\Diario;
use INHack20\ConsultorioBundle\Form\DiarioType;
use INHack20\ConsultorioBundle\Form\ConsultType;
use INHack20\ConsultorioBundle\Model\Search;

/**
 * Diario controller.
 *
 * @Route("/diario")
 */
class DiarioController extends Controller
{
   private $options = array('municipio' => 'Municipio',
                        'asic' => 'Asic',
                        'consultorio' => 'Consultorio',
            );
   
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
     * @Route("/consult", name="diario_consult") 
     * @Template()
     */
    public function consultAction(){
        $request = $this->getRequest();
      
        $form = $this->createForm(new ConsultType($this->options));  
    
        if($request->getMethod() == 'POST' && $request->isXmlHttpRequest()){
            $class = "INHack20ConsultorioBundle:Diario";
            $view = 'INHack20ConsultorioBundle:Diario:index_content.html.twig';
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
     * @Route("/exportSearchPDF", name="diario_export_search")
     */
    public function exportSearchPDFAction(){
        
        $pdf= $this->get('white_october.tcpdf')->create();
        $translator = $this->get('translator');
       
        $logo = $this->get('templating.helper.assets')->getUrl('bundles/inhack20consultorio/images/logo_barrio.jpg');
        // set document information
        $pdf->SetCreator('Symfony2 PDF');
        $pdf->SetAuthor('Barrio Adentro I');
        $pdf->SetTitle('Reporte');
        $pdf->SetSubject('Barrio Adentro I');
        $pdf->SetKeywords('Barrio Adentro');
        $pdf->setTranslator($translator);
        $pdf->setTitulo($translator->trans('header.5',array(),'pdf'));
        $pdf->setLogo($logo);
        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        $pdf->AddPage();
        
        $data = $this->getRequest()->query->all();
        $class = "INHack20ConsultorioBundle:Diario";
        $view = 'INHack20ConsultorioBundle:Diario:index_content.html.twig';
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        $entities = Search::getSearchResult($this->options, $class, $view, $qb, true, $data);
        
$html ='<table border="1" style="width: 100%; text-align:center; ">
        <tr>
            <th style="width: 4%;">Nº</th>
            <th style="width: 25%;">MEDICO</th>
            <th style="width: 20%;">MUNICIPIO</th>
            <th style="width: 20%;">ASIC</th>
            <th style="width: 15%;">CONSULTORIO</th>
            <th style="width: 15%;">FECHA</th>
        </tr>';
if($entities){
    $i = 1;
    foreach ($entities as $entity) {
        $html.='
                <tr>
                    <td>'.$i.'</td>
                    <td>'.$entity->getMedico().'</td>
                    <td>'.$entity->getMunicipio().'</td>
                    <td>'.$entity->getAsic().'</td>
                    <td>'.$entity->getConsultorio().'</td>
                    <td>'.$entity->getFechaCreado()->format('Y-m-d').'</td>
                </tr>
             ';
        $i++;
    }
}else{
     $html.='
            <tr>
                <td colspan="6">No se encontraron resultados</td>
            </tr>
         ';
}
$html.='
</table>
';

$pdf->SetFont('helvetica', '', 9);
$pdf->writeHTML($html, true, false, true, false, '');

       return new \Symfony\Component\HttpFoundation\Response($pdf->Output('example_006.pdf', 'I'),
                200,array('Content-Type' => 'application/pdf'));
    }

    /**
     * Exporta los resultados de un filtro a un archivo pdf
     * @Route("/{id}/exportPDF", name="diario_exportPDF")
     */
    public function exportPDFAction($id){
        
       $em = $this->getDoctrine()->getManager();

       $entity = $em->getRepository('INHack20ConsultorioBundle:Diario')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Diario entity.');
        }
       
        $pdf= $this->get('white_october.tcpdf')->create();
        $logo = $this->get('templating.helper.assets')->getUrl('/bundles/inhack20consultorio/images/logo_barrio.jpg');
        $translator = $this->get('translator');
        
        // set document information
        $pdf->SetCreator('Symfony2 PDF');
        $pdf->SetAuthor('Barrio Adentro I');
        $pdf->SetTitle('Reporte');
        $pdf->SetSubject('Barrio Adentro I');
        $pdf->SetKeywords('Barrio Adentro');
        $pdf->setTranslator($translator);
        $pdf->setTitulo($translator->trans('header.4',array(),'pdf'));
        $pdf->setLogo($logo);
        $pdf->setResume(true);
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
                    <td>'.$translator->trans('title.nombreMedico',array(),'pdf').':'.$entity->getMedico()->getNombreCompleto().'</td>
                    <td>'.$translator->trans('title.fecha',array(),'pdf').':'.$entity->getFechaCreado()->format('Y-m-d').'</td>
                </tr>
                <tr>
                    <td>'.$translator->trans('title.municipio',array(),'pdf').':'.$entity->getMunicipio().'</td>
                    <td>'.$translator->trans('title.asic',array(),'pdf').':'.$entity->getAsic().'</td>
                    <td>'.$translator->trans('title.consultorio',array(),'pdf').':'.$entity->getConsultorio().'</td>
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
        </tr>';
$pacientes = $entity->getPacientes();
if($pacientes){
   $i=0;
   foreach ($pacientes as $paciente){
   $html.='<tr>
            <td>'.($i + 1 ).'</td>
            <td>'.$paciente->getCedula().'</td>
            <td>'.$paciente->getNombreCompleto().'</td>
            <td>'.$paciente->getEdad().'</td>
            <td>'.$paciente->getSexo().'</td>
            <td></td>
            <td>'.$paciente->getTipoConsulta()->getAcronimo().'</td>
            <td>'.$paciente->getDiagnostico().'</td>
            <td>'.$paciente->getTratamiento().'</td>
        </tr>';
        $i++;
   }//fin foreach
   $pdf->setTotal($i);
}
else{
   $html.='<tr>
            <td colspan="9">No se encontraron resultados.</td>
        </tr>';
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
