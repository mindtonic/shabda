<?php

class captcha
{
	public function embed()
	{
		$return = '
		<img src="vendors/captcha/CaptchaSecurityImages.php" /><br />
		<input id="security_code" name="security_code" type="text" />';
		if ($_SESSION['captcha_error'])
		{
		  $return .= '<div class="errors">'.$_SESSION['captcha_error'].'</div>';
			unset($_SESSION['captcha_error']);
		}
		return $return;
	}
	
	public function validate()
	{
		if( $_SESSION['security_code'] == $_POST['security_code'] && !empty($_SESSION['security_code'] ) )
		{
		  return true;
			unset($_SESSION['security_code']);
		}
		else
		{
		  $_SESSION['captcha_error'] = 'Incorrect Validation Code';
			return false;
		}
	}
}

?>
