<?php
/**
 * Export a sample HTML page to PDF using `Dompdf`.
 *
 * @author NDF, 16-June-2021.
 */

require __DIR__ . '/pdf-lib.php';

const HTML_FILE = __DIR__ . '/test.html';
const CSS_FILE = __DIR__ . '/style/app.css'; // Was: 'print.css';

$pdflib = filter_input(INPUT_GET, 'lib', FILTER_VALIDATE_REGEXP, [
  'options' => [
    'regexp' => '/^(dompdf|tcpdf|mpdf)$/',
  ]
]);

header('X-PDF-Lib:' . $pdflib);


switch ($pdflib) {
  case 'dompdf':
    domPdf(HTML_FILE);
    break;

  case 'tcpdf':
    tcPdf(HTML_FILE);
    break;

  case 'mpdf':
    mPdf(HTML_FILE);
    break;

  default:
    echo "ERROR: Unexpected `pdflib` = $pdflib";
}
