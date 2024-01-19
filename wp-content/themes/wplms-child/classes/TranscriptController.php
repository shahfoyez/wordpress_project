<?php
class TranscriptController{
	private $user_id;
	private $user_name;
	private $user_email;
	private $course_id;
	private $filepath;
	private $db_filepath;
	private $error_messages = array();
	private $curriculums;
	private $pdf;
	public function __construct(){
		require_once get_stylesheet_directory() . '/vendor/autoload.php';

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
				if ( $this->GeneratePdf() ) {
					$wpdb->query('COMMIT');
					// stores the pdf if no error is returned
					$this->pdf->Output('F', $this->filepath);
//					$user_mail = $this->SendUserNotification();
					return [
						'success' => true,
//						'mail_status' => $user_mail
					];
				}
				$wpdb->query('ROLLBACK');
			}
			// send notification to admin if an error is occurred
//			$this->SendAdminNotification();
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
//		if(empty($this->course_id)){
//			$this->error_messages[] = "Please Select a Course";
//			return false;
//		}
//		$course = get_post($this->course_id);
//		if (!$course || is_wp_error( $course )) {
//			$this->error_messages[] = "Course Not Found";
//			return false;
//		}
//		$status = bp_course_get_user_course_status($this->user_id, $this->course_id);
//		if ($status != 4) {
//			$this->error_messages[] = "Please Complete the course first!";
//			return false;
//		}

//		$meta_key = 'vibe_certificate_template';
//		$certificate_template_id = get_post_meta($this->course_id, $meta_key, true);
//		if(!is_numeric($certificate_template_id)){
//			$this->error_messages[] = "Certificate template not assigned!";
//			return false;
//		}
//		$this->certificate_id = $certificate_template_id;
		return true;
	}
	public function GeneratePdf(){
//		http://localhost/MyProject/wp-content/uploads/2024/01/t1.png
//		http://localhost/MyProject/wp-content/uploads/2024/01/t2.png
//		http://localhost/MyProject/wp-content/uploads/2024/01/t3.png
		$title = get_the_title($this->course_id);
		$_pdf = new FPDF('P', 'mm', 'A4');
		$this->pdf = $_pdf;
		$completion_date = $this->GetCourseCompletionDate();

		$get_curriculum = $this->GetCurriculum();
		$chunkSize = 2;
		$chunks = array_chunk($this->curriculums, $chunkSize, true);
		$curriculums_chunk_array = array();

		foreach ($chunks as $key => $chunk) {
			$curriculums_chunk_array[$key] = $chunk;
		}



		$totalItems = count($curriculums_chunk_array);
		$start = 1;
		foreach ($curriculums_chunk_array as $chunk_key => $chunk){
			$isLast = ($chunk_key == $totalItems - 1);
			if($start == 1){
				$columnHeight = 10;
				$image_url = 'http://localhost/MyProject/wp-content/uploads/2024/01/t1.png';
			}else if ($chunk_key == $isLast) {
				$columnHeight = 10;
				$image_url = 'http://localhost/MyProject/wp-content/uploads/2024/01/t3.png';
			}else{
				$image_url = 'http://localhost/MyProject/wp-content/uploads/2024/01/t2.png';
				$columnHeight = 14;
			}
			// Get image dimensions
			list($originalWidth, $originalHeight) = getimagesize($image_url);

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
			$_pdf->Image($image_url, $xPosition, $yPosition, $newWidth, $newHeight);

			$textWidth = 145;
			$centeredX = $xPosition + ($pageWidth - $textWidth) / 2;

			if($start == 1){
				// Set the name
				$_pdf->SetFont('Arial', '', 9);
				$_pdf->SetXY(60, $yPosition + 0.7 * $newHeight / 4);
				$_pdf->MultiCell($textWidth, 30, $this->user_name, 0, 'L', 0);

				// set the title
				$length = strlen($title);
				$_pdf->SetFont('Arial', '', 9);
				$_pdf->SetXY(60, $yPosition + 0.94 * $newHeight / 4);
				$_pdf->MultiCell($textWidth, 8, $title, 0, 'L', 0);

				// set date
				$_pdf->SetFont('Arial', '', 9);
				$_pdf->SetXY(60, $yPosition + 0.88 * $newHeight / 4);
				$_pdf->MultiCell($textWidth, 30, $completion_date, 0, 'L', 0);
			}

			// set table
			$tableWidth = 137; // Set the width of the table
			$tableX = ($pageWidth - $tableWidth) / 2;
			$tableY = $start === 0 ? $yPosition + 1 * $newHeight / 4 : $yPosition + 1.35 * $newHeight / 4;
			$_pdf->SetXY($tableX, $tableY);

			$leftColumnWidth = $tableWidth * 0.3;
			$rightColumnWidth = $tableWidth * 0.7;

			$_pdf->SetDrawColor(0, 0, 0);

			foreach($chunk as $key => $value) {
				$_pdf->SetX($tableX);
				$_pdf->Cell($leftColumnWidth, $columnHeight, $key, 'TL', 0, 'C');
				$_pdf->Cell($rightColumnWidth, $columnHeight, $value, 'TLR', 1, 'C');
			}
			// Draw bottom border for the last row
			$_pdf->SetX($tableX);
			$_pdf->Cell($tableWidth, 0, '', 'T');
			$start = 0;
		}



		$_pdf->Output();

//		$pdf_filename = 'certificate_' . $this->user_id . '_' . $this->course_id . '_' . uniqid( '', true ) . '.pdf';
//		$pdf_filepath = WP_CONTENT_DIR . '/uploads/sa-certificate-pdf/' . $pdf_filename;
//		$pdf_db = wp_upload_dir()['baseurl'] . '/sa-certificate-pdf/' . $pdf_filename;
//		$this->filepath = $pdf_filepath;
//		$this->db_filepath = $pdf_db;
//
//		return true;
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
	private function GetCurriculum() {
		$units = bp_course_get_curriculum_units($this->course_id);
		if ($units && is_array($units)) {
			$curriculums = array();
			$key = 1;
			foreach ($units as $unitID) {
				if( get_post_type($unitID) === 'unit'){
					$unitTitle =get_the_title($unitID) ;
					$moduleNumber = 'Module ' . ($key);
					$curriculums[$moduleNumber] = $unitTitle;
					$key++;
				}
			}
			$this->curriculums = $curriculums;
			return true;
		}
		$this->error_messages[] = "Curricular not found";
		return false;
	}
}