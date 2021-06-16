<?php
/**
 * Export a sample HTML page to PDF using `Dompdf`.
 *
 * @author NDF, 16-June-2021.
 */

require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;

const HTML_FILE = __DIR__ . '/test.html';

const OPTIONS = [
  'chroot' => __DIR__,
  'defaultMediaType' => 'print',
  'isHtml5ParserEnabled' => true,
  'isJavascriptEnabled' => false,
  'debugKeepTemp' => true,
  'debugCss' => true,
  'logOutputFile' => __DIR__ . '/dompdf.log.html',
];

$dompdf = new Dompdf(OPTIONS);

$dompdf->loadHtmlFile(HTML_FILE, 'UTF-8');
// $dompdf->loadHtml('<!doctype html><html><title>hello world</title><h1>Hello</h1></html>');

// (Optional) Setup the paper size and orientation
// $dompdf->setPaper('A4', 'landscape');
$dompdf->setPaper('A5', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// header('Content-Type: application/pdf');
// header('Content-Disposition: attachment; filename="test.pdf"');

// Output the generated PDF to Browser
$dompdf->stream('my-document.pdf', [ 'attachment' => 0 ]);
