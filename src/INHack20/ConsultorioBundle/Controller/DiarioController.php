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
     * @Route("/consult", name="diario_consult") 
     * @Template()
     */
    public function consultAction(){
        $request = $this->getRequest();
        $options = array('medico' => 'Médico',
                        'municipio' => 'Municipio',
                        'asic' => 'Asic',
                        'consultorio' => 'Consultorio',
            );
        $form = $this->createForm(new ConsultType($options));  
        $entities = NULL;
        $data = NULL;
        if($request->getMethod() == 'POST' && $request->isXmlHttpRequest()){
            $form->bindRequest($request);
            if($form->isValid()){
                $data = $form->getData();
                $busqueda = NULL;
                $criterio = NULL;
                $fechaDesde = NULL;
                $fechaHasta = NULL;
                if($data['busqueda']){
                    $busqueda = $data['busqueda'];
                }
                if($data['criterio']){
                    $criterio = $data['criterio'];
                }
                if($data['fechaDesde']){
                    $fechaDesde = $data['fechaDesde'];
                }
                if($data['fechaHasta']){
                    $fechaHasta = $data['fechaHasta'];
                }
                
                $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
                $qb->select('d')->from('INHack20ConsultorioBundle:Diario', 'd');
                    if($busqueda && $criterio){
                        $qb->andWhere($qb->expr()->like('d.'.$busqueda,"'%".$criterio."%'"));
                    }
                    if($fechaDesde && !$fechaHasta){
                        $qb->andWhere($qb->expr()->like('d.fechaCreado',"'".$fechaDesde->format('Y-m-d')."%'"));
                    }
                    if($fechaDesde && $fechaHasta){
                        $qb->andWhere('d.fechaCreado >= :fechaCreado');
                        $qb->andWhere('d.fechaCreado <= :fechaCreado2');
                        $qb->setParameters(new \Doctrine\Common\Collections\ArrayCollection(array(
                            new \Doctrine\ORM\Query\Parameter('fechaCreado',$fechaDesde->format('Y-m-d')),
                            new \Doctrine\ORM\Query\Parameter('fechaCreado2',$fechaHasta->format('Y-m-d').' 23:59:59'),
                        )));
                    }
                    //echo $qb->getQuery()->getSQL();
                    //echo $fechaDesde->format('Y-m-d');
                    $entities = $qb->getQuery()->getResult();
                var_dump($data);
            }
            return $this->render('INHack20ConsultorioBundle:Diario:index_content.html.twig',array(
                'entities' => $entities,
                'data' => $data,
            ));
        }
        
        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * Exporta los resultados de un filtro a un archivo pdf
     * @Route("/exportPDF", name="diario_export_diario")
     * @Template
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

// print a block of text using Write()
//$pdf->Write($h=0, $txt, $link='', $fill=0, $align='J', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
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
}
