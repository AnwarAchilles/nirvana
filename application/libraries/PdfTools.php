<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// panggil autoload dompdf nya

require_once APPPATH.'/third_party/Dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdftools {
    public function generate($html, $filename='', $paper = '', $orientation = '', $stream=TRUE)
    {   
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->load_html($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        if ($stream) {
            $dompdf->stream($filename.".pdf", array("compress" => true, "Attachment" => 0));
        } else {
            return $dompdf->output();
        }
    }
}
