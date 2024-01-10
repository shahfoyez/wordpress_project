<?php
/* Template Name: Order Error */
?>
<?php
// Retrieve the error message from the URL
$error_message = isset($_GET['error_message']) ? urldecode($_GET['error_message']) : 'An error occurred.';
?>
<div class="error-message">
	<p><?php echo esc_html($error_message); ?></p>
</div>
