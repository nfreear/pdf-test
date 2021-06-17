/**
 *
 * @author NDF, 17-June-2021.
 */

const PDF_LIB_REGEX = /[\?&]lib=(dompdf|tcpdf|mpdf)/;

const EXP_LINK = document.querySelector('a[ href *= "export.php" ]');

const DEF_LIB = param(PDF_LIB_REGEX, null, EXP_LINK.href);
const PDF_LIB = param(PDF_LIB_REGEX, DEF_LIB);

if (PDF_LIB) {
  EXP_LINK.href = EXP_LINK.href.replace(PDF_LIB_REGEX, `lib=${PDF_LIB}`);
}

console.warn('PDF Library:', PDF_LIB, EXP_LINK.href);

// ----------------------------------------------

function param(regex, def = null, inp = window.location.href) {
  const matches = inp.match(regex);

  return matches ? matches[1] : def;
}
