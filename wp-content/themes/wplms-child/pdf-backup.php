<?php
/* Template Name: CustomPagePdf */


$user_id = get_current_user_id();
$meta_key = 'certificates';
$course_id = "75";
$certificates = get_user_meta($user_id, $meta_key, true);
$title = get_post($course_id)->post_title;

//echo "<pre>";
//var_dump($certificates);
//
//var_dump(serialize($certificates));
//echo "</pre>";

//if (!in_array($course_id, $certificates)) {
//	$certificates[] = $course_id;
//	update_user_meta($user_id, $meta_key, $certificates);
//}
//$certificates = get_user_meta($user_id, $meta_key, true);
//echo "<pre>";
////var_dump($certificates);
////
////var_dump(serialize($certificates));
////echo "</pre>";
////die();


//require 'includes/pdfGeneration/fpdf.php';
require_once get_stylesheet_directory() . '/vendor/autoload.php';


$_pdf = new FPDF('P', 'mm', 'A4'); // Set page orientation and size to A4

// Get image dimensions
list($originalWidth, $originalHeight) = getimagesize('https://adamsfcstg.wpenginepowered.com/wp-content/uploads/2023/12/Adams_Academy__-_Certificate_.png');

// Calculate scaling factor based on page width and image aspect ratio
$pageWidth = $_pdf->GetPageWidth();
$scaleFactor = $pageWidth / $originalWidth;

// Calculate new image width and height based on scaling factor
$newWidth = $originalWidth * $scaleFactor;
$newHeight = $originalHeight * $scaleFactor;

// Center the image horizontally
$xPosition = ($_pdf->GetPageWidth() - $newWidth) / 2;
$yPosition = ($_pdf->GetPageHeight() - $newHeight) / 2;

// Add the image to the PDF
$_pdf->AddPage();
$_pdf->Image('https://adamsfcstg.wpenginepowered.com/wp-content/uploads/2023/12/Adams_Academy__-_Certificate_.png', $xPosition, $yPosition, $newWidth, $newHeight);

$textWidth = 145;
$centeredX = $xPosition + ($pageWidth - $textWidth) / 2;

// Set the name
$_pdf->SetFont('Arial', '', 40);
$_pdf->SetXY($centeredX, $yPosition + 1.4 * $newHeight / 4);
$_pdf->MultiCell($textWidth, 30, 'Shove Hossain', 0, 'C', 0);


// set the title
//$title = "Advanced Diploma in Copy Writing Advanced Diploma in Copy Writing ";
$length = strlen($title);
$font_size = $length > 40 ? 16 : 24;
$position = $length > 40 ? 1.89 : 1.94;;
$_pdf->SetFont('Arial', '', $font_size);
$_pdf->SetXY($centeredX, $yPosition + $position * $newHeight / 4);
$_pdf->MultiCell($textWidth, 8, $title, 0, 'C', 0);

// set date
$_pdf->SetFont('Arial', '', 16);
$_pdf->SetXY($centeredX, $yPosition + 2.23 * $newHeight / 4);
$_pdf->MultiCell($textWidth, 30, '5 December 2023', 0, 'C', 0);

// set serial number
$_pdf->SetFont('Arial', '', 16);
$_pdf->SetXY($centeredX, $yPosition + 2.44 * $newHeight / 4);
$_pdf->MultiCell($textWidth, 30, 'IEMK1234MMKHJGY', 0, 'C', 0);


// Page 2
$_pdf->AddPage();
$_pdf->Image('https://adamsfcstg.wpenginepowered.com/wp-content/uploads/2023/12/Adams_Academy__-_Certificate_.png', $xPosition, $yPosition, $newWidth, $newHeight);


$textWidth = 145;
$centeredX = $xPosition + ($pageWidth - $textWidth) / 2;

// Set the name
$_pdf->SetFont('Arial', '', 40);
$_pdf->SetXY($centeredX, $yPosition + 1.4 * $newHeight / 4);
$_pdf->MultiCell($textWidth, 30, 'Arif Ali', 0, 'C', 0);


// set the title
$title = "Arif Has successfully completed ";
$length = strlen($title);
$font_size = $length > 40 ? 16 : 24;
$position = $length > 40 ? 1.89 : 1.94;;
$_pdf->SetFont('Arial', '', $font_size);
$_pdf->SetXY($centeredX, $yPosition + $position * $newHeight / 4);
$_pdf->MultiCell($textWidth, 8, $title, 0, 'C', 0);

// set date
$_pdf->SetFont('Arial', '', 16);
$_pdf->SetXY($centeredX, $yPosition + 2.23 * $newHeight / 4);
$_pdf->MultiCell($textWidth, 30, '5 December 2023', 0, 'C', 0);

// set serial number
$_pdf->SetFont('Arial', '', 16);
$_pdf->SetXY($centeredX, $yPosition + 2.44 * $newHeight / 4);
$_pdf->MultiCell($textWidth, 30, 'IEMK1234MMKLJKHH', 0, 'C', 0);

// update user meta
if (!in_array($course_id, $certificates)) {
	$certificates[] = $course_id;
	update_user_meta($user_id, $meta_key, $certificates);
}

// Save the PDF to a variable
$pdf_content = $_pdf->Output('', 'S');

// Output only a part of the PDF content
$preview_length = 500; // Set the length of the preview
$preview_content = substr($pdf_content, 0, $preview_length);

// Display the preview content
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="preview.pdf"');
header('Content-Length: ' . strlen($preview_content));
echo $preview_content;

// Insert data into custom_pdf table
$pdf_filename = 'certificate_' . $user_id . '_' . uniqid() . '.pdf';
$pdf_filepath = WP_CONTENT_DIR . '/uploads/custom-pdf/' . $pdf_filename;
$_pdf->Output('F', $pdf_filepath);
global $wpdb;
$table_name = 'custom_pdf';

$data = array(
	'user_id' => $user_id,
	'course_id' => $course_id,
	'pdf_path' => $pdf_filepath,
);
$wpdb->insert($table_name, $data);

// view pdf
//$_pdf->Output();
