<?php

namespace INHack20\ConsultorioBundle\Model;

/**
 * Description of Search
 *
 * @author Angelical
 */
class Search {
   /**
     * 
     * @param array() $options
     * @param string $class
     * @param string $view
     * @return viewHtml
     */
    public static function getSearchResult($options,$class,$view,$qb,$returnEntity = false,$data = NULL ){
             $entities = NULL;
        
             if(isset($data['data'])){
                  $data = $data['data'];
             }    
             $busqueda = NULL;
             $criterio = NULL;
             $fechaDesde = NULL;
             $fechaHasta = NULL;
             $medico = NULL;
             $tipoConsulta = NULL;
             
             $result = false;
             if($data){
                foreach ($data as $value) {
                    if($value){
                        $result = true;
                        break;
                    }
                }
             }
             if($result){
                 if(isset($data['busqueda'])){
                     $busqueda = $data['busqueda'];
                 }
                 if(isset($data['criterio'])){
                     $criterio = $data['criterio'];
                 }
                 if(isset($data['fechaDesde'])){
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
                 if(isset($data['medico'])){
                     $medico = $data['medico'];
                     if(is_object($medico)){
                         $data['medico'] = $medico->getId();
                         $medico = $data['medico'];
                     }
                 }
                 if(isset($data['tipoconsulta'])){
                    $tipoConsulta = $data['tipoconsulta'];
                     if(is_object($tipoConsulta)){
                         $data['tipoconsulta'] = $tipoConsulta->getId();
                         $tipoConsulta = $data['tipoconsulta'];
                     }
                 }
                
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
                     if($medico){
                         $qb->andWhere('d.medico = :medico');
                         $qb->setParameter('medico', $medico);
                     }
                     
                     if($tipoConsulta){
                        $qb->andWhere('d.tipoConsulta = :tipoConsulta');
                        $qb->setParameter('tipoConsulta',$tipoConsulta);
                     }

                     $entities = $qb->getQuery()->getResult();
                 }//fin if
            if($returnEntity){
                return $entities;
            }
            else{
                return array(
                    'entities' => $entities,
                    'data' => $data,
                );
            }
    }
}