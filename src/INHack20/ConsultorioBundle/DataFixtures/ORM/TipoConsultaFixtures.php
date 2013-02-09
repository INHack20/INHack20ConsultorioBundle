<?php

namespace INHack20\ConsultorioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use INHack20\ConsultorioBundle\Entity\TipoConsulta;

/**
 * Description of TipoConsultaFixtures
 *
 * @author inhack20
 */
class TipoConsultaFixtures implements FixtureInterface {
    
    public function load(ObjectManager $manager) {
        $tipoConsulta = new TipoConsulta();
        $tipoConsulta->setAcronimo('CN');
        $tipoConsulta->setSignificado('Consulta Nueva');
        
        $manager->persist($tipoConsulta);
        
        $tipoConsultaRC = new TipoConsulta();
        $tipoConsultaRC->setAcronimo('RC');
        $tipoConsultaRC->setSignificado('Re-Consulta');
        
        $manager->persist($tipoConsultaRC);
        
        $manager->flush();
    }
}