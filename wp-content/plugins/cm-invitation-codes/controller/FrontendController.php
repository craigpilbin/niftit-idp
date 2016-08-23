<?php

namespace com\cminds\registration\controller;

use com\cminds\registration\model\Settings;

use com\cminds\registration\App;

use com\cminds\registration\model\Labels;

class FrontendController extends Controller {
	
	static $actions = array(
		'wp_head',
		'wp_enqueue_scripts' => array('method' => 'includeAssets'),
		'login_enqueue_scripts' => array('method' => 'includeAssets'),
	);
	static $ajax = array('cmreg_login_overlay');
	
	
	static function includeAssets() {
		
		if (!App::isLicenseOk()) return;
		
		wp_enqueue_script('cmreg-frontend');
		wp_enqueue_style('cmreg-frontend');
		
		wp_localize_script('cmreg-frontend', 'CMREG_Settings', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'isUserLoggedIn' => intval(is_user_logged_in()),
			'logoutUrl' => wp_logout_url(),
			'logoutButtonLabel' => Labels::getLocalized('logout_button'),
		));
		
	}
	
	
	static function wp_head() {
		echo '<style type="text/css">';
		if ($css = Settings::getOption(Settings::OPTION_CUSTOM_CSS)) {
			echo $css;
		}
		$opacity = Settings::getOption(Settings::OPTION_OVERLAY_OPACITY);
		if (!is_numeric($opacity)) $opacity = 70;
		echo PHP_EOL . '.cmreg-overlay {background: rgba(0,0,0,'. ($opacity/100) .') !important;}' . PHP_EOL;
		echo '</style>';
	}
	
	
	static function getOverlayView($atts = array()) {
		$content = LoginController::getLoginFormView($atts) . RegistrationController::getRegistrationFormView($atts);
		return self::loadFrontendView('overlay', compact('content', 'atts'));
	}
	
	
	static function getLoginButton($loginButtonText, $atts) {
		if (is_user_logged_in()) {
			$loginButtonText = Labels::getLocalized('logout_button');
			$href = wp_logout_url();
		} else {
			if (empty($loginButtonText)) {
				$loginButtonText = Labels::getLocalized('login_button');
			}
			$href = '#';
		}
		return self::loadFrontendView('login-button', compact('loginButtonText', 'atts', 'href'));
	}
	
	
	static function cmreg_login_overlay() {
		echo self::getOverlayView();
		exit;
	}
	
	
}
