<?php

require_once('modules/recaptcha/recaptchalib.php');

class recaptcha
{
	private $public_key = "6Ld3SAcAAAAAALUrq8x5JYiwmKZ6WYZbZrI_ESu9";
	private $private_key = "6Ld3SAcAAAAAAORXuaMPEv0B-11_gBI5kBCoXCSA";

	public function embed()
	{
		return recaptcha_get_html($this->public_key);
	}

	public function validate()
	{
		$privatekey = "...";
		$resp = recaptcha_check_answer ($this->private_key,
		                                $_SERVER["REMOTE_ADDR"],
		                                $_POST["recaptcha_challenge_field"],
		                                $_POST["recaptcha_response_field"]);
		
		return $resp->is_valid ? true : false;
	}
}

?>