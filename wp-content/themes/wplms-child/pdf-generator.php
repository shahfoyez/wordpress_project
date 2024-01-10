<?php
/* Template Name: CustomPagePdf */



$meta_key = 'certificates';
$user_id = get_current_user_id();
$certificates = get_user_meta($user_id, $meta_key, true);
echo "<pre>";
var_dump($certificates);
echo "</pre>";
//die();
require get_stylesheet_directory() . '/classes/CertificateController.php';
$course_id = "76";
$course_id = (string)$course_id;

$certificate_controller = new CertificateController();
$PdfGenerated = $certificate_controller->UpdateUserCertificate($course_id);




