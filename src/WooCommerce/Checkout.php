<?php

namespace Tribe\Project\WooCommerce;

class Checkout {

	public function customize_wc_mailchimp_before_opt_in_checkbox() {
		echo '<p id="gdpr-label"><strong>'. __( 'Optional Marketing Permissions', 'tribe' ) .'</strong></p>';
		echo '<p id="gdpr-description">'. __( 'Cordoba Guitars will use the information you provide on this form for updates and marketing. Check below to receive our monthly newsletter.', 'tribe' ) .'</p>';
	}

	public function customize_wc_mailchimp_after_opt_in_checkbox() {
		echo '<p id="gdpr-legal">'. __( 'You can change your mind at any time by clicking the unsubscribe link in the footer of any email you receive from us. We will treat your information with respect. For more information about our privacy practices please visit our privacy policy. By clicking below, you agree that we may process your information in accordance with these terms.', 'tribe' ) .'</p>';
		echo '<p id="gdpr-footer"><a href="https://www.mailchimp.com/gdpr" target="_blank" style="float:left; margin-right: 15px;"><img src="https://cdn-images.mailchimp.com/icons/mailchimp-gdpr.svg" alt="GDPR" style="width:65px;height:65px"></a> '. __( 'We use MailChimp as our marketing automation platform. By clicking below to submit this form, you acknowledge that the information you provide will be transferred to MailChimp for processing in accordance with their', 'tribe') .' <a href="https://mailchimp.com/legal/privacy/" target="_blank">'. __( 'Privacy Policy', 'tribe' ) .'</a> '. __( 'and', 'tribe' ) .' <a href="https://mailchimp.com/legal/terms/" target="_blank">'. __( 'Terms', 'tribe' ) .'</a>.</p>';
	}

}
