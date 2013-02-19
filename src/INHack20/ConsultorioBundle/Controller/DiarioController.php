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
    
        if($request->getMethod() == 'POST' && $request->isXmlHttpRequest()){
            $class = "INHack20ConsultorioBundle:Diario";
            $view = 'INHack20ConsultorioBundle:Diario:index_content.html.twig';
            
            return $this->getSearchResult($options, $class, $view);
            
        }
        
        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * Exporta los resultados de un filtro a un archivo pdf
     * @Route("/exportPDF", name="diario_export_diario")
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
        
        $options = array('medico' => 'Médico',
                        'municipio' => 'Municipio',
                        'asic' => 'Asic',
                        'consultorio' => 'Consultorio',
            );
        
        $data = $this->getRequest()->query->all();
        
        $class = "INHack20ConsultorioBundle:Diario";
        $view = 'INHack20ConsultorioBundle:Diario:index_content.html.twig';
        $entities = $this->getSearchResult($options, $class, $view, true,$data);
        
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
                $busqueda = NULL;
                $criterio = NULL;
                $fechaDesde = NULL;
                $fechaHasta = NULL;
                
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
