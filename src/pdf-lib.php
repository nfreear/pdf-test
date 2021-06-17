<?php
/**
 * Functions wrapping the three PDF library options!
 *
 * @author NDF, 17-June-2021.
 */

require __DIR__ . '/../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

const HTML_SAMPLE = <<<EOF
<!doctype html>
<style>
  h1 { color: red; }
</style>

<h1> Hello world! </h1>
<p><a href="https://example.org">I'm a link!</a></p>
EOF;

/**
 * mPDF library.
 */
const MPDF_OPTIONS = [
  'mode' => 'utf-8',
  'format' => 'A4',
  'orientation' => 'P',
  'debug' => true,
];

function mPdf ($htmlFile, $cssFile = CSS_FILE) {
  $html = file_get_contents($htmlFile);
  $stylesheet = file_get_contents($cssFile);

  $logger = new Logger('name');
  $logger->pushHandler(new StreamHandler(__DIR__ .'/mpdf.log', Logger::DEBUG));

  $mpdf = new \Mpdf\Mpdf(MPDF_OPTIONS);

  $mpdf->setLogger($logger);

  /* $mpdf->setLogger(new class extends \Psr\Log\AbstractLogger {
    public function log($level, $message, array $context = []) {
        echo $level . ': ' . $message . "\n";
    }
  }); */

  $mpdf->SetTitle('Test Document 1 (mPDF)');
  $mpdf->SetAuthor('Nick F.');

  $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
  $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

  $mpdf->Output('mpdf-document-01.pdf', \Mpdf\Output\Destination::DOWNLOAD);
}

/**
 * TCPDF library.
 */
const STYLE_REGEX = '@<link[^>]+(app.css)"[^>]\/?>@';

function tcPdf ($htmlFile, $cssFile = CSS_FILE) {
  $HTML = file_get_contents($htmlFile);
  $CSS = file_get_contents($cssFile);

  $STYLE_SHEET = "<style> $CSS </style>"; // "<style>/* <![CDATA[ */\n $CSS\n/* ]]> */\n</style>";

  // $HTML = preg_replace(STYLE_REGEX, $STYLE_SHEET, $HTML);

  // echo $HTML;
  // return;

  $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

  $tcpdf->SetTitle('Test Document 1 (TCPDF)');
  $tcpdf->SetAuthor('Nick F.');

  // $tcpdf->SetFont('dejavusans', '', 14, '', true);

  $tcpdf->AddPage();

  $tcpdf->writeHTML($HTML, true, false, true, false, '');

  // $tcpdf->lastPage();

  // 'I' = inline to browser/stdout; 'D' = download; 'F' = file;
  $tcpdf->Output('tcpdf-document-01.pdf', 'D');
}

/**
 * Dompdf library.
 */
const DOMPDF_OPTIONS = [
  'chroot' => __DIR__,
  'defaultMediaType' => 'print',
  'defaultPaperSize' => 'A4',
  'defaultPaperOrientation' => 'portrait',
  'defaultFont' => 'DejaVu Serif',
  'fontDir' => __DIR__ . '/../vendor/dompdf/dompdf/lib/fonts/',
  'isHtml5ParserEnabled' => true,
  'isJavascriptEnabled' => true,
  'debugKeepTemp' => true,
  'debugCss' => true,
  'debugLayout' => true,
  'logOutputFile' => __DIR__ . '/dompdf.log.html',
];

function domPdf ($htmlFile) {
  $dompdf = new \Dompdf\Dompdf(DOMPDF_OPTIONS);

  $dompdf->loadHtmlFile($htmlFile, 'UTF-8');
  // $dompdf->loadHtml('<!doctype html><html><title>hello world</title><h1>Hello</h1></html>');

  // (Optional) Setup the paper size and orientation
  // $dompdf->setPaper('A4', 'landscape');
  $dompdf->setPaper('A5', 'portrait');

  // Render the HTML as PDF
  $dompdf->render();

  // header('Content-Type: application/pdf');
  // header('Content-Disposition: attachment; filename="test.pdf"');

  // Output the generated PDF to Browser
  $dompdf->stream('dompdf-document-01.pdf', [ 'attachment' => 0 ]);
}
