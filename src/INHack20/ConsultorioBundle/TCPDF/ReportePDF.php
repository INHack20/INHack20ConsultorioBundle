<?php

namespace INHack20\ConsultorioBundle\TCPDF;


//require_once 'config/tcpdf_config_alt.php';
/**
 * Description of ReportePDF
 *
 * @author Angelical
 */
class ReportePDF extends \TCPDF {
    
    private $translator;
    private $titulo;
    private $resume;
    private $total;
    private $logo;
    
    public function __construct($orientation = 'L', $unit = 'mm', $format = 'letter', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
    }
    public function Header() {
        // Logo
        $image_file = $this->logo;
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
        $this->Cell(0, 15, $this->translator->trans('header.1',array(),'pdf'), 0, true, 'C', 0, '', 0, false, 'M', 'M');
        $this->Cell(0, 15, $this->translator->trans('header.2',array(),'pdf'), 0, true, 'C', 0, '', 0, false, 'M', 'M');
        $this->Cell(0, 15, $this->translator->trans('header.3',array(),'pdf'), 0, true, 'C', 0, '', 0, false, 'M', 'M');
        $this->Cell(0, 15, $this->titulo, 0, true, 'C', 0, '', 0, false, 'M', 'M');
    }
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        $html='<table style="width: 100%">
        <tr>
            <td style="text-align: left">';
       if($this->resume){ 
         $html.='TOTAL DE CASOS VISTOS: '.$this->total.'<br/>';
       }
       $html.='</td>
            <td style="text-align: right">FIRMA:</td>
        </tr>
        </table>';
        $this->SetY(-35);
       $this->writeHTML($html, true, false, true, false, '');
        
    }
    public function setTranslator($translator) {
        $this->translator = $translator;
    }
    public function setTitulo($titulo) {
       $this->titulo = $titulo;
    }
    public function setResume($resume) {
       $this->resume = $resume;
    }
    public function setTotal($total) {
       $this->total = $total;
    }
    public function setLogo($logo) {
       $this->logo = $logo;
    }
}