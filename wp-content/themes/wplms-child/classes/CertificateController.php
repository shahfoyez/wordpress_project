<?php
class CertificateController{
	private $user_id;
	private $user_name;
	private $user_email;
	private $current_datetime;
	private $course_id;
	private $filepath;
	private $error_messages = array();
	private $pdf;
	public function __construct(){
		$this->user_id = get_current_user_id();
		$user_data = get_userdata($this->user_id);
		$this->user_name = $user_data->display_name;
		$this->user_email = $user_data->user_email;
		$this->current_datetime = current_time('mysql');
	}
	public function UpdateUserCertificate($course_id) {
		$this->course_id = $course_id;
		global $wpdb;
		$wpdb->query('START TRANSACTION');
		try {
			if ( $this->ValidateCourse() ) {
				if ($this->UpdateMeta() && $this->GeneratePdf() && $this->AddCertificateAutomation()) {
					$wpdb->query('COMMIT');
					$this->pdf->Output('F', $this->filepath);
					return [
						'success' => true,
					];
				}
				$wpdb->query('ROLLBACK');
			}
			return [
				'success' => false,
				'errors' => $this->error_messages
			];
		} catch (Exception $e) {
			$wpdb->query('ROLLBACK');
			return [
				'success' => false,
				'errors' => $this->error_messages
			];
//			$error_message = $e->getMessage();
//			$this->redirect_to_order_error($error_message);
		}
	}
	public function ValidateCourse() {
		if(empty($this->course_id)){
			$this->error_messages[] = "Please Select a Course";
			return false;
		}
		$course = get_post($this->course_id);
		if (!$course || is_wp_error( $course )) {
			$this->error_messages[] = "Course Not Found";
			return false;
		}
//		$status = bp_course_get_user_course_status($this->course_id, $this->user_id);
//		if ($status !== 4) {
//			$this->error_messages[] = "Please Complete the course first!";
//			return false;
//		}
		return true;
	}
	public function UpdateMeta() {
		$meta_key = 'certificates';
		$certificates = get_user_meta($this->user_id, $meta_key, true) ?: array();
		if (!in_array($this->course_id, $certificates)) {
			$certificates[] = $this->course_id;
			$update_result = update_user_meta($this->user_id, $meta_key, $certificates);
			if ($update_result === false) {
				$this->error_messages[] = "Failed to update user meta.";
				return false;
			}
			return true;
		}
		$this->error_messages[] = "You have already purchased the certificate!";
		return false;
	}
	public function GeneratePdf(){
//		require_once get_stylesheet_directory() . '/includes/library/vendor/autoload.php';
		require_once get_stylesheet_directory() . '/vendor/autoload.php';
		$title = get_the_title($this->course_id);
		$_pdf = new FPDF('P', 'mm', 'A4');
		$this->pdf = $_pdf;

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
		$_pdf->MultiCell($textWidth, 30, $this->user_name, 0, 'C', 0);

		// set the title
		$length = strlen($title);
		$font_size = $length > 40 ? 16 : 24;
		$position = $length > 40 ? 1.89 : 1.94;
		$_pdf->SetFont('Arial', '', $font_size);
		$_pdf->SetXY($centeredX, $yPosition + $position * $newHeight / 4);
		$_pdf->MultiCell($textWidth, 8, $title, 0, 'C', 0);

		// set date
		$_pdf->SetFont('Arial', '', 16);
		$_pdf->SetXY($centeredX, $yPosition + 2.23 * $newHeight / 4);
		$_pdf->MultiCell($textWidth, 30, date('j F Y', strtotime($this->current_datetime)), 0, 'C', 0);

		// set serial number
		$_pdf->SetFont('Arial', '', 16);
		$_pdf->SetXY($centeredX, $yPosition + 2.44 * $newHeight / 4);
		$_pdf->MultiCell($textWidth, 30, 'IEMK1234MMKHJGY', 0, 'C', 0);


		$pdf_filename = 'certificate_' . $this->user_id . '_' . $this->course_id . '_' . uniqid() . '.pdf';
		$pdf_filepath = WP_CONTENT_DIR . '/uploads/sa-certificate-pdf/' . $pdf_filename;
		$this->filepath = $pdf_filepath;
		return true;
	}
	public function AddCertificateAutomation(){
		// Insert data into custom_pdf table
		global $wpdb;
		$table_name = 'wp_sa_certificate_automation';
//		$table_name = 'custom_pdf';

		$data = array(
			'user_id' => $this->user_id,
			'course_id' => $this->course_id,
			'cert_id' => "IIDJDHDH",
			'pdf_path' => $this->filepath,
			'pdf_type' => 'certificate',
			'assigned_date' => $this->current_datetime
		);
		$result = $wpdb->insert($table_name, $data);

		if ( ! $result ) {
			$this->error_messages[] = $wpdb->last_error;
			return false;
		}
		return true;
	}

	public function CourseCompletionDate() {
		global $wpdb;
		$query = $wpdb->prepare("
		    SELECT activity.date_recorded
		    FROM {$wpdb->prefix}bp_activity AS activity
		    WHERE
		        activity.component = 'course'
		        AND activity.type = 'unit_complete'
		        AND activity.user_id = %d
		        AND activity.item_id = %d
		    ORDER BY date_recorded DESC
		    LIMIT 1",
				1027,
				2111
		);

		$date = $wpdb->get_var($query);
	}

	public function SendEmailNotification() {

	}
}