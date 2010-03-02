<?php

/**
	FORM BUILDER 0.2.4 (9-19-08)

		API:
		
		form() : returns entire form

		fields() : returns only form fields

		start_form() : returns the opening form line
		end_form() : returns the closing form line

		submit_button() : returns the submit button
*/

class form_builder
{
	private $model;
	public $controller;
	public $action;
	private $form = array();
	//- Options
	private $default_ignore_form_fields = array('id','created_at','updated_at','created_by','edited_by','updated_by');
	public $file_upload = false;
	public $id = null;
	public $boolean = 'radio';
	public $wysiwyg = false;
	#public $wysiwyg_type = 'wyzz';
	public $wysiwyg_type = 'fckeditor';
	public $toolset = 'default';
	public $captcha = false;
	public $size = 60;
	public $rows = 20;
	public $cols = 80;
	public $relationships = array();
	
	function __construct(Model $model, $controller = false, $action = false)
	{
		$this->model = ($model);
		$this->controller = $controller;
		$this->action = $action;
	}

	### API

	public function form()
	{
	  $this->build_form();
		return implode('', $this->form);
	}

	public function fields()
	{
		$this->assemble();
		return implode('', $this->form);
	}
	
	public function add_ignore_field($field)
	{
		array_push($this->default_ignore_form_fields, $field);
	}
	
	public function end_form_with_fields()
	{
		$return = $this->fields();
		$return .= $this->submit_button();
		$return .= $this->end_form();
		return $return;
	}
	
	public function single_field($method, $name)
	{
		if (method_exists($this, $method))
			return '
			<div class="form">	
				'.$this->label($name).'
				'.$this->$method($name).'
				'.$this->errors_on($name).'
			</div>
			';
	}
	
	### FORM TAGS
	
	function start_form()
	{
		// Default Values
		$controller = $this->controller ? $this->controller : $this->model->table;
		$action = $this->action ? $this->action : 'update';
	
		// Determine proper id to submit
		if ($this->id) $id = $this->id;
		elseif ($this->model->id) $id = $this->model->id;
		else $id = false;
		
		// Build Proper Form Support
		return '
<form name="'.$this->model->table.'" action="'.inner_link($controller,$action,$id).'" method="post"'.($this->file_upload == true ? ' enctype="multipart/form-data"' : '').'>';
	}
	
	function custom_start_form($controller, $action, $other = false)
	{
		return '
<form name="'.$controller.'" action="?c='.$controller.'&amp;a='.$action.($other ? '&amp;'.$other : '').'" method="post"'.'>';
	}
	
	function end_form()
	{
		return'
		</form>';	
	}
	
	### LABEL

	function label($label)
	{
		return '
		<label name="'.$label.'">'.humanize($label).'</label>';
	}
	
	### INPUT TEXT
	
	function input_text($name, $options = null)
	{		
		return '
		<input type="text" name="'.$this->name($name).'"  value="'.$this->value($name).'" size="'.$this->size.'"'.$this->extract_options($options).' />';
	}
	
	### TEXTAREA
	
	function textarea($name, $options = null)
	{
		if ($this->wysiwyg)
			return $this->wysiwyg($name);
		else
			return '
			<textarea name="'.$this->name($name).'" rows="'.$this->rows.'" cols="'.$this->cols.'"'.$this->extract_options($options).'>'.$this->value($name).'</textarea>';
	}
	
	function wysiwyg($name, $options = null)
	{
		if ($this->wysiwyg_type == 'fckeditor')
		  return $this->fckeditor($name, $options);
		elseif ($this->wysiwyg_type == 'wyzz')
		  return $this->wyzz($name, $options);
	}
	
	function fckeditor($name, $options = null)
	{
		include_once("vendors/fckeditor/fckeditor.php") ;

		$editor = new FCKeditor($this->name($name));
		$editor->BasePath = "vendors/fckeditor/";
		$editor->Value = $this->value($name);
		#$editor->ToolbarSet = $this->toolset;
		$editor->Width  = 800;
		$editor->Height = 600;
		
		return $editor->Create();
	}
	
	function wyzz($name, $options = null)
	{
	  return '
	  <textarea name="'.$this->name($name).'" id="'.$this->id($name).'" rows="'.$this->rows.'" cols="'.$this->cols.'">'.$this->value($name).'</textarea><br />
	  <script language="javascript1.2">
	    make_wyzz(\''.$this->id($name).'\');
	  </script>';
	}
	
	### Hidden
	
	function input_hidden($name, $options = null)
	{
		return '
		<input type="hidden" name="'.$this->name($name).'"  value="'.$this->value($name).'"'.$this->extract_options($options).' />';
	}
	
	function custom_hidden($name, $value, $options = null)
	{
		return '
		<input type="hidden" name="'.$this->name($name).'"  value="'.$value.'"'.$this->extract_options($options).' />';
	}
	
	function hidden_field($name, $value, $options = null)
	{
		return '
		<input type="hidden" name="'.$name.'"  value="'.$value.'"'.$this->extract_options($options).' />';
	}

	### Boolean
	
	function boolean($name, $options = null)
	{
		if ($this->boolean == 'radio')
		  return $this->boolean_radio($name, $options);
		else
			return $this->boolean_select($name, $options);
	}
	
	function boolean_select($name, $options = null)
	{
		return '
		<select name="'.$this->name($name).'"'.$this->extract_options($options).'>
			<option value="1" '.($this->value($name) ? 'selected="1"' : '').'>YES</option>
			<option value="0" '.(!$this->value($name) ? 'selected="1"' : '').'>NO</option>
		</select>';
	}
	
	function boolean_radio($name, $options = null)
	{
		return '
		<input type="radio" name="'.$this->name($name).'" value="1" '.($this->value($name) ? 'checked' : '').$this->extract_options($options).'>YES
		<input type="radio" name="'.$this->name($name).'" value="0" '.(!$this->value($name) ? 'checked' : '').$this->extract_options($options).'>NO';
	}
	
	function unset_boolean_radio($name, $options = null)
	{
		return '
		<input type="radio" name="'.$this->name($name).'" value="1" '.$this->extract_options($options).'>YES
		<input type="radio" name="'.$this->name($name).'" value="0" '.$this->extract_options($options).'>NO';
	}
	
	### SELECT

	function dropdown($name, $options, $selected = false)
	{
		$return = '
		<select name="'.$this->name($name).'">';
		foreach ($options as $key => $value)
		  $return .= '
			<option value="'.$key.'"'.($this->model->$name == $key ? ' selected="1"' : '').'>'.$value.'</option>';
		$return .= '
		</select>';
		return $return;
	}
	
	### DATETIME
	
	function datetime($name)
	{
		$date = new make_datetime($this->model->$name ? $this->model->$name : null);
		$date->set_select_name($this->model->table,$this->model->table.'[',']');
	
		return $date->select_day().$date->select_month().$date->select_year();
	}
	
	function time($name)
	{
		$date = new make_datetime($this->model->$name ? $this->model->$name : null);
		$date->set_select_name($this->model->table,$this->model->table.'['.$name.'][',']');

		return $date->select_hour().$date->select_minute().$date->select_ampm();
	}
	
	### FILE UPLOAD
	
	function file_upload()
	{
		return '<input type="file" name="image" size="30" />';
	}
	
	### SUBMIT
	
	function submit_button($text = 'save', $options = null)
	{
		return '<input type="submit" value="'.$text.'" class="save" />';
	}
	
	### ERRORS
	
	function errors_on($attribute)
	{
		if ($this->model->errors_on($attribute))
			return '<div class="errors">'.$this->model->errors_on($attribute).'</div>';
	}
	
	### FORM BUILDERS
	
	private function build_form()
	{
		$this->form[] = $this->start_form();
		$this->assemble();
		$this->form[] = '<div class="form">'.$this->submit_button().'</div>';
		$this->form[] = $this->end_form();
	}

	private function assemble()
	{
		foreach ($this->model->table_data as $field => $type)
		{
			$valid = true;

			$panel = '
			<div class="form">';
			$panel .= $this->label($field);

			if (in_array($field, $this->model->ignore_form_fields()))
				continue;
			elseif (in_array($field, $this->default_ignore_form_fields))
			  continue;
			elseif (array_key_exists($field, $this->relationships))
				$panel .= $this->build_relationships($field, $this->relationships[$field]);
			elseif (ereg('tinyint', $type))
				$panel .= $this->boolean($field);
			elseif (ereg('int', $type))
				$panel .= $this->input_text($field);
			elseif (ereg('varchar', $type))
				$panel .= $this->input_text($field);
			elseif (ereg('text', $type))
				$panel .= $this->textarea($field);
			elseif (ereg('datetime', $type))
				$panel .= $this->datetime($field);
			elseif (ereg('time', $type))
				$panel .= $this->time($field);
			else $valid = false;

			$panel .= $this->errors_on($field);
			$panel .= "
			</div>";

			if ($valid) $this->form[] = $panel;
		}
		
		if ($this->file_upload) $this->build_file_upload();
		if ($this->captcha) $this->captcha();
	}
	
	private function build_file_upload()
	{
		$panel = '<div class="form">';
		$panel .= $this->label('image');		
		if ($this->model->thumbnail()) $panel .= $this->model->thumbnail().'<br /><br />';
		$panel .= $this->file_upload();
		$panel .= $this->errors_on('image');
		$panel .= "</div>";		
	
		$this->form[] = $panel;	
	}
	
	private function build_relationships($field, $table)
	{
		$parent = new $table;
		$objects = $parent->load_all();

		$return = '
		<select name="'.$this->name($field).'">';
		
		foreach ($objects as $object)
		{
			if ($this->name) $return .= '
				<option value="'.$object->id.'"'.($object->id == $this->model->$field ? ' selected="1"' : '').'>'.$object->name.'</option>';
			elseif (method_exists($object, 'display_name')) $return .= '
				<option value="'.$object->id.'"'.($object->id == $this->model->$field ? ' selected="1"' : '').'>'.$object->display_name().'</option>';
			else continue;
		}
		
		$return .= '
		</select>';
		
		return $return;	
	}
	
	### UTILITIES
	
	function extract_options($options)
	{
		if ($options) extract($options);

		$data['id'] = $id ? 'id="'.$id.'"' : null;
		$data['class'] = $class ? 'class="'.$class.'"' : null;
		$data['size'] = $size ? 'size="'.$size.'"' : null;
		$data['rows'] = $rows ? 'rows="'.$rows.'"' : null;
		$data['cols'] = $cols ? 'cols="'.$cols.'"' : null;

		return ' '.implode(' ', $data);
	}
	
	function name($name)
	{
		return $this->model->table.'['.$name.']';
	}
	
	function id($name)
	{
		return $this->model->table.'_'.$name;
	}
	
	function value($name)
	{
		if ($this->model->$name)
		  return $this->model->$name;
		elseif ($this->model->defaults[$name])
		  return $this->model->defaults[$name];
		else return false;
	}
	
	function captcha()
	{
    $captcha = new captcha;
    $this->form[] = '<div class="form">'.$captcha->embed().'</div>';
	}

}

?>
