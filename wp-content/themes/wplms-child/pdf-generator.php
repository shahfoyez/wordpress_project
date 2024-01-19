<?php
/* Template Name: CustomPagePdf */



$meta_key = 'certificates';
$user_id = get_current_user_id();
$certificates = get_user_meta($user_id, $meta_key, true);

//die();
//require get_stylesheet_directory() . '/classes/CertificateController.php';
require get_stylesheet_directory() . '/classes/CTController.php';
$course_id = "76";
$course_id = (string)$course_id;

//$certificate_controller = new CertificateController();
//$PdfGenerated = $certificate_controller->UpdateUserCertificate($course_id);

$transcript_controller = new CTController();
$certificate_clicked = $transcript_clicked = true;
$PdfGenerated = $transcript_controller->UpdateUserCT($course_id, true, true );
 echo "<pre>";
 var_dump($PdfGenerated);
 echo "</pre>";
 






