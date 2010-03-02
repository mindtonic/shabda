<?php

class contact_controller extends Controller
{
	function before_filter()
	{
		$this->view->active_menu_item = "contact";
	}
	
	function index()
	{
		$form = new contact();
		$this->view->item = $form;
	}
	
	function update()
	{
		$form = new contact();
		$captcha = new captcha;
		
		if ($this->registry->request('contact'))
		{
			$form->set_attributes($this->registry->request('contact'));
			if ($form->validate() && $captcha->validate())
			{
				$form->save();
				$mailer = new Mailer();
				$mailer->contact($form);
				$this->redirect('users', 'thanks');
			}
		}

		$this->view->item = $form;
		$this->view->view = "index";
		$this->view->layout = "application";
	}

}


?>
