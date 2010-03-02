<?php

/*
	USAGE:
	
	$calendar = new jscalendar($this->item, 'date');
	$this->head = $calendar->javascript();
	
	$content = $form->start_form();
	
	$content .= '
	<div class="form">
		'.$form->label('date').'
		'.$calendar->form_fields().'
		'.$form->errors_on('date').'
	</div>
	
	'.$calendar->render_scripts().'
	'.$form->end_form_with_fields();
*/

class jscalendar
{
	private $model;
	private $field;
	private $path = 'vendors/jscalendar/';

	function __construct(Model $model, $field)
	{
		$this->model = $model;
		$this->field = $field;
	}

	public function javascript()
	{
		return '
<style type="text/css">@import url('.$this->path.'calendar-win2k-1.css);</style>
<script type="text/javascript" src="'.$this->path.'calendar.js"></script>
<script type="text/javascript" src="'.$this->path.'lang/calendar-en.js"></script>
<script type="text/javascript" src="'.$this->path.'calendar-setup.js"></script>';
	}

	public function form_fields()
	{
		return '
		<div style="width: 250px;" id="calendar-container"></div>
		<input type="hidden" name="'.$this->model->table.'['.$this->field.']"  value="" id="hidden_date" />
	';	
	}

	public function render_scripts()
	{
		$return = '
<script type="text/javascript">
  function mysqlTimeStampToDate(timestamp) {
    //function parses mysql datetime string and returns javascript Date object
    //input has to be in this format: 2007-06-05 15:26:02
    var regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
    var parts=timestamp.replace(regex,"$1 $2 $3 $4 $5 $6").split(\' \');
    return new Date(parts[0],parts[1]-1,parts[2],parts[3],parts[4],parts[5]);
  }

  function dateChanged(calendar) {
      // Assign to Hidden Field
      document.getElementById("hidden_date").value = calendar.date;
  };

  Calendar.setup(
    {';
    if ($this->model->value($this->field) > 0) 
    	$return .= 'date         : mysqlTimeStampToDate("'.$this->model->value($this->field).'"),';
		$return .= '      
			flat         : "calendar-container", // ID of the parent element
      flatCallback : dateChanged           // our callback function
    }
  );
</script>';
		return $return;
	}
}

?>