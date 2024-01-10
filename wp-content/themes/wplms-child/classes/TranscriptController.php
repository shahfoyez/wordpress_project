<?php
class TranscriptController{
	private $user_id;
	private $user_name;
	private $user_email;
	private $course_id;
	private $filepath;
	private $db_filepath;
	private $error_messages = array();
	private $certificate_id;
	private $serial_number;
	private $pdf;
	public function __construct(){
		$this->user_id = get_current_user_id();
		$user_data = get_userdata($this->user_id);
		$this->user_name = $user_data->display_name;
		$this->user_email = $user_data->user_email;
	}
	public function UpdateUserTranscript($course_id) {
		$this->course_id = $course_id;
		global $wpdb;
		$wpdb->query('START TRANSACTION');
		try {
			if ( $this->ValidateCourse() ) {
				if ($this->UpdateMeta() && $this->GeneratePdf() && $this->AddCertificateAutomation()) {
					$wpdb->query('COMMIT');
					// stores the pdf if no error is returned
					$this->pdf->Output('F', $this->filepath);
					$user_mail = $this->SendUserNotification();
					return [
						'success' => true,
						'mail_status' => $user_mail
					];
				}
				$wpdb->query('ROLLBACK');
			}
			// send notification to admin if an error is occurred
			$this->SendAdminNotification();
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
//		$status = bp_course_get_user_course_status($this->user_id, $this->course_id);
//		if ($status != 4) {
//			$this->error_messages[] = "Please Complete the course first!";
//			return false;
//		}

		$meta_key = 'vibe_certificate_template';
		$certificate_template_id = get_post_meta($this->course_id, $meta_key, true);
		if(!is_numeric($certificate_template_id)){
			$this->error_messages[] = "Certificate template not assigned!";
			return false;
		}
		$this->certificate_id = $certificate_template_id;
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
		require_once get_stylesheet_directory() . '/includes/library/vendor/autoload.php';
		$title = get_the_title($this->course_id);
		$_pdf = new FPDF('P', 'mm', 'A4');
		$this->pdf = $_pdf;
		$completion_date = $this->GetCourseCompletionDate();
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
		$_pdf->MultiCell($textWidth, 30, $completion_date, 0, 'C', 0);

		// set serial number
		$_pdf->SetFont('Arial', '', 16);
		$_pdf->SetXY($centeredX, $yPosition + 2.44 * $newHeight / 4);
		$serial_number = $this->certificate_id."-".$this->course_id."-".$this->user_id;
		$this->serial_number = $serial_number;
		$_pdf->MultiCell($textWidth, 30, $serial_number, 0, 'C', 0);


		$pdf_filename = 'certificate_' . $this->user_id . '_' . $this->course_id . '_' . uniqid( '', true ) . '.pdf';
		$pdf_filepath = WP_CONTENT_DIR . '/uploads/sa-certificate-pdf/' . $pdf_filename;
		$pdf_db = wp_upload_dir()['baseurl'] . '/sa-certificate-pdf/' . $pdf_filename;
		$this->filepath = $pdf_filepath;
		$this->db_filepath = $pdf_db;

		return true;
	}
	public function AddCertificateAutomation(){
		// Insert data into wp_sa_certificate_automation table
		global $wpdb;
		$table_name = 'wp_sa_certificate_automation';
		$data = array(
			'user_id' => $this->user_id,
			'course_id' => $this->course_id,
			'cert_id' =>  $this->serial_number,
			'pdf_path' => $this->db_filepath,
			'pdf_type' => 'certificate',
			'assigned_date' => current_time('mysql')
		);
		$result = $wpdb->insert($table_name, $data);

		if ( ! $result ) {
			$this->error_messages[] = $wpdb->last_error;
			return false;
		}
		return true;
	}
	public function GetCourseCompletionDate() {
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
			$this->user_id,
			$this->course_id
		);
		$date = $wpdb->get_var($query);
		if($date){
			return date('j F Y', strtotime($date));
		}
		return false;
	}
	public function SendUserNotification() {
		$to = $this->user_email;
		$subject = 'Your Certificate';
		$message = 'Dear ' . $this->user_name . ',<br><br>';
		$message .= 'Please find your certificate attached.<br>';
		$message .= 'Thank you!<br>';

		$headers = array('Content-Type: text/html; charset=UTF-8');

		$attachments = array(
			$this->filepath
		);
		return wp_mail($to, $subject, $message, $headers, $attachments);
	}
	public function SendAdminNotification() {
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
//		$admin_email = get_option('admin_email');
		$admin_email   = 'shoivehossain@staffasia.org';
		$admin_subject = 'Error in Certificate Generation';
		$admin_message = 'User: ' . $this->user_name . ',<br><br>';
		$admin_message .= 'Course: ' . $this->course_id . ',<br><br>';
		$admin_message .= 'Error messages:<br>';
		$admin_message .= implode( '<br>', $this->error_messages );

		return wp_mail( $admin_email, $admin_subject, $admin_message, $headers );
	}
	function GetUserCertificates(){
		$user_id = get_current_user_id();
		global $wpdb;
		$table_name = 'wp_sa_certificate_automation';
		$query = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE user_id = %d",
			$user_id
		);
		$results = $wpdb->get_results($query, ARRAY_A);
		if($results){
			return $results;
		}
		return false;
	}
	function GetCertificate($course_id){
		$user_id = get_current_user_id();
		global $wpdb;
		$table_name = 'wp_sa_certificate_automation';
		$query = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE user_id = %d AND course_id = %d",
			$user_id,
			$course_id
		);
		$result = $wpdb->get_results($query, ARRAY_A);
		if($result){
			return $result;
		}
		return false;
	}
}