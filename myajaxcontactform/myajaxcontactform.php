<?php
/*
    Plugin Name: ajaxcontactform
    Plugin URI: https://www.munshaipinfosys.com/ajaxcontactform
    Description: Add a simple banner image to your (posts and pages) shortcode  [my_ajax_contact_form]
    Author: Shailesh Parmar.
    Version: 1.0
    Author URI: https://www.munshaipinfosys.com/
*/

function ajaxcontactform() {
	$submit_url = get_site_url() . '/wp-content/plugins/myajaxcontactform/includes/submit.php';
	$javascript_file = get_site_url() . '/wp-content/plugins/myajaxcontactform/includes/contactform.js';
	$admin_email = get_option( 'admin_email' );

	echo '<form action="" role="form" method="post" id="contact_form">';
	echo '<div id="name-group" class="form-group">';
	echo '<label for="name">Name:</label>';
	echo '<input class="form-control" type="text" name="name" >';
	echo '</div>';
	echo '<div id="email-group" class="form-group">';
	echo '<label for="email">Email:</label>';
	echo '<input class="form-control" type="email" name="email"  >';
	echo '</div>';
	echo '<div id="subject-group" class="form-group">';
	echo '<label for="subject">Subject:</label>';
	echo '<input class="form-control" type="text" name="subject" >';
	echo '</div>';
	echo '<div id="message-group" class="form-group">';
	echo '<label for="message">Message:</label>';
	echo '<textarea id="message" class="form-control" rows="10" name="message"></textarea>';
	echo '</div>';
	echo '<input class="form-control" type="hidden" name="admin_email" value="' . $admin_email . '" >';
	echo '<input class="form-control" type="hidden" name="url" value="' . $submit_url . '" >';
	echo '<div id="captcha-group" class="form-group">';
	echo '<label for="captcha">Captcha:</label>';
	echo '<div class="g-recaptcha" data-sitekey="YOUR-RECAPTCHA-PUBLIC-KEY-GOES-HERE"></div>';
	echo '</div>';
	echo '<button type="submit" class="btn btn-default">Send Message</button>';
	echo '</form>';

	echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
	echo '<script src="' . $javascript_file . '" </script>';
}

function contactform_start() {
	ob_start();
	ajaxcontactform();
	return ob_get_clean();
}

add_shortcode( 'my_ajax_contact_form', 'contactform_start' );
?>
