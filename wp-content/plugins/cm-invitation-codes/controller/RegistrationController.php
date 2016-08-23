<?php

namespace com\cminds\registration\controller;

use com\cminds\registration\App;

use com\cminds\registration\lib\Email;

use com\cminds\registration\model\InvitationCode;

use com\cminds\registration\model\Labels;

use com\cminds\registration\model\Settings;

use com\cminds\registration\model\User;

class RegistrationController extends Controller {
	
	const REGISTRATION_NONCE = 'cmreg_registration_nonce';
	const FIELD_PASS = 'cmregpw';
	const FIELD_REPEAT_PASS = 'cmregpwrepeat';
	
	static $actions = array(
		'init',
		'register_new_user' => array('args' => 1, 'priority' => 500),
	);
	static $ajax = array('cmreg_registration');
	
	
	static function init() {
	}
	
	
	
	static function getRegistrationFormView($atts) {
		$nonce = wp_create_nonce(self::REGISTRATION_NONCE);
		return self::loadFrontendView('registration-form', compact('nonce', 'atts'));
	}
	

	static function cmreg_registration() {
		
		if (!App::isLicenseOk()) return;
		
		$response = array('success' => false, 'msg' => Labels::getLocalized('register_error_msg'));
		
		if (static::isRegistrationAction() AND !empty($_POST['email']) AND !empty($_POST[static::FIELD_PASS])) {
			
			$login = (empty($_POST['login']) ? '' : $_POST['login']);
			$displayName = (empty($_POST['display_name']) ? $_POST['email'] : $_POST['display_name']);
			$email = (is_email($_POST['email']) ? $_POST['email'] : null);
			
			try {
				
				if (Settings::getOption(Settings::OPTION_REGISTER_REPEAT_PASS_ENABLE)) {
					if (empty($_POST[static::FIELD_REPEAT_PASS]) OR $_POST[static::FIELD_REPEAT_PASS] != $_POST[static::FIELD_PASS]) {
						throw new \Exception(Labels::getLocalized('register_repeat_pass_error_msg'));
					}
				}
				
				// The email and other params will be validated inside this method and throw exception if invalid:
				
				$userId = User::register($email, $_POST[static::FIELD_PASS], $login, $displayName);
				
				$status = User::getEmailVerificationStatus($userId);
				if (User::EMAIL_VERIFICATION_STATUS_PENDING == $status) {
					User::logout();
					$response = array(
						'success' => true,
						'msg' => Labels::getLocalized('register_verification_msg'),
					);
				} else {
					User::login($email, $_POST[static::FIELD_PASS], false);
					$response = array(
						'success' => true,
						'msg' => Labels::getLocalized('register_success_msg'),
						'redirect' => 'reload',
					);
				}
				
			} catch (\Exception $e) {
				$response['msg'] = $e->getMessage();
			}
			
		}
// 		var_dump($response);
// 		exit;
		
		header('content-type: application/json');
		echo json_encode($response);
		exit;
	}
	
	
	static function isRegistrationAction() {
		return (isset($_POST['nonce']) AND wp_verify_nonce($_POST['nonce'], self::REGISTRATION_NONCE));
	}
	
	
	/**
	 * After successful registration
	 * 
	 * @param unknown $userId
	 */
	static function register_new_user($userId) {
		if (!App::isLicenseOk()) return;
		if (User::EMAIL_VERIFICATION_STATUS_VERIFIED == User::getEmailVerificationStatus($userId)) {
			Email::sendWelcomeEmail($userId);
		}
	}
	
	
	static function getWelcomeUrl() {
		if ($pageId = Settings::getOption(Settings::OPTION_REGISTER_WELCOME_PAGE)) {
			return get_permalink($pageId);
		} else {
			return site_url();
		}
	}
	
	
}
