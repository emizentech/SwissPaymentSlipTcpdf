<?php
/**
 * Example of SwissPaymentSlipFpdf Creating one thousand payment slips
 *
 * PHP version >= 5.3.0
 *
 * @licence MIT
 * @copyright 2012-2013 Some nice Swiss guys
 * @author Manuel Reinhard <manu@sprain.ch>
 * @author Peter Siska <pesche@gridonic.ch>
 * @author Marc Würth ravage@bluewin.ch
 * @link https://github.com/sprain/class.Einzahlungsschein.php
 * @version: 0.5.0
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>SwissPaymentSlip Example 03-01: SwissPaymentSlipFpdf Creating one thousand payment slips</title>
</head>
<body>
<h1>SwissPaymentSlip Example 03-01: SwissPaymentSlipFpdf Creating one thousand payment slips</h1>
<?php
// Measure script execution/generating time
$time_start = microtime(true);

// Make sure the classes get auto-loaded
require __DIR__.'/../vendor/autoload.php';

// Import necessary classes
use SwissPaymentSlip\SwissPaymentSlip\SwissPaymentSlipData;
use SwissPaymentSlip\SwissPaymentSlip\SwissPaymentSlip;
use SwissPaymentSlip\SwissPaymentSlip\SwissPaymentSlipFpdf;
use fpdf\FPDF;

// Make sure FPDF has access to the additional fonts
define('FPDF_FONTPATH', __DIR__.'/../src/SwissPaymentSlip/SwissPaymentSlip/Resources/font');

// Create an instance of FPDF, setup default settings
$fPdf = new FPDF('P','mm','A4');

// Add OCRB font to FPDF
$fPdf->AddFont('OCRB10');

// create 1000 payment slips
for ($slipNr = 1; $slipNr <= 1000; $slipNr++) {
	// Add page, don't break page automatically
	$fPdf->AddPage();
	$fPdf->SetAutoPageBreak(false);

	// Insert a dummy invoice text, not part of the payment slip itself
	$fPdf->SetFont('Helvetica','',9);
	$fPdf->Cell(50, 4, "Just some dummy text.");

	// Create an payment slip data container (value object)
	$paymentSlipData = new SwissPaymentSlipData(); // for better performance, take outside of the loop

	// Fill the data container with your data
	$paymentSlipData->setBankData('Seldwyla Bank', '8001 Zürich');
	$paymentSlipData->setAccountNumber('01-145-6');
	$paymentSlipData->setRecipientData('H. Muster AG', 'Versandhaus', 'Industriestrasse 88', '8000 Zürich');
	$paymentSlipData->setPayerData('Rutschmann Pia', 'Marktgasse 28', '9400 Rorschach', 'Slip # ' . $slipNr);
	$paymentSlipData->setAmount(2830.50);
	$paymentSlipData->setReferenceNumber('7520033455900012');
	$paymentSlipData->setBankingCustomerId('215703');

	// Create an payment slip object, pass in the prepared data container
	$paymentSlip = new SwissPaymentSlip($paymentSlipData, 0, 191); // for better performance, take outside of the loop

	// Create an instance of the FPDF implementation
	$paymentSlipFpdf = new SwissPaymentSlipFpdf($fPdf, $paymentSlip); // for better performance, take outside of the loop

	// "Print" the slip with its elements according to their attributes
	$paymentSlipFpdf->createPaymentSlip();
}

// Output PDF named example_03-03.pdf to examples folder
$fPdf->Output(__DIR__ . DIRECTORY_SEPARATOR . 'example_03-03.pdf', 'F');

echo "Payment slip created in " . __DIR__ . DIRECTORY_SEPARATOR . 'example_03-03.pdf <br>';

echo "<br>";

$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Generation took $time seconds <br>";
echo 'Peak memory usage: ' . memory_get_peak_usage() / 1024 / 1024;
?>
</body>
</html>