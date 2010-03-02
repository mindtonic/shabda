<?php

class make_datetime
{
	public $stamp;
	public $str;

	function __construct($date = null)
	{
		$this->stamp = $this->get_stamp($date);
		$this->str = date('Y-m-d',$this->stamp);
		$this->start_month();
		$this->start_year();
		$this->stop_year();
	}
	
	function get_stamp($date = null)
	{
		if (!isset($date)) $date = $this->stamp ? $this->stamp : time();
		return @preg_match("/\D/",$date) ? strtotime($date) : $date;
	}
	
	function set_timezone($timezone = 'EST5EDT')
	{
		putenv("TZ=$timezone");
	}	

	function set_select_name($prefix = null, $namePrefix = null, $namePostfix = null)
	{
		$this->prefix = $prefix ? $prefix."_" : null;
		$this->namePrefix = $namePrefix;
		$this->namePostfix = $namePostfix;
	}

	
	
#############################################################################
#############################################################################
# STYLES
	function without_styles($bool = false)
	{
		$this->without_styles = $bool;
	}
	
	function get_style($which)
	{
		if ($this->without_styles) return null;
		
		switch ($which)
		{
			case 'month':
			case 'ampm':
				return " style='width:60px'";
			case 'year':
				return " style='width:70px'";
			case 'hour':
				return " style='width:39px'";
			case 'minute':
			case 'day':
				return " style='width:50px'";
		}
	}

	function get_select($name, $options)
	{
		if ($this->options_only) return $options;
			
		$output = "\t<select name='".$this->namePrefix.$name.$this->namePostfix."'";
		$output .= " id='".$this->prefix.$name."'";
		$output .= $this->get_style($name);
		$output .= $this->get_javascript();
		if ($this->disabled) $output .= " disabled='1'";
		$output .= ">\n";
		$output .= $options;
		$output .= "\t</select>\n";
		return $output;
	}
	



#############################################################################
#############################################################################
# JAVASCRIPT
	function get_javascript()
	{
		if ($this->javascript) return $this->javascript;
		
		if ($this->uses_javascript) return " onchange=\"dateSelect('$this->prefix')\"";
	}

	function write_javascript()
	{
		if ($this->uses_javascript)
			return "\t<script type='text/javascript' src='_javascripts/date_select.js'></script>\n";
	}


#############################################################################
#############################################################################
# MONTH
	function month_num($date = null, $format = 'n')
	{
		return date($format, $this->get_stamp($date) );
	}

	function Month($date = null, $format = 'M')
	{
		return date($format, $this->get_stamp($date) );
	}

	function start_month($month_num = 1)
	{
		$this->start_month = $month_num;
	}
	
	function day_num($date = null, $format = 'j')
	{
		return date($format, $this->get_stamp($date) );
	}
	
	function num_days($date = null)
	{
		return date('t', $this->get_stamp($date) );
	}

	function select_month($format = 'M')
	{
		for ($n=1; $n <= 12; $n++) if ($n >= $this->start_month)
		{
			$options .= "\t<option value='$n'";
			if (!$this->without_selected && $this->month_num()==$n)
				$options .= " selected='1'";
			$options .= ">".$this->Month("$n/1/".$this->Year(),$format)."</option>\n";
		}
		return $this->get_select('month',$options);
	}
	


#############################################################################
#############################################################################
# DAY
	function select_day($value_format = 'j', $text_format = 'j')
	{
		for ($d = 1; $d <= $this->num_days($this->stamp); $d++)
		{
			$v = $this->day_num($this->month_num()."/$d/".$this->Year(), $value_format);
			$t = $this->day_num($this->month_num()."/$d/".$this->Year(), $text_format);
			$options .= "\t<option value='$v'";
			if (!$this->without_selected && $this->day_num()==$d)
				$options .= " selected='1'";
			$options .= ">$t</option>\n";
		}
		return $this->get_select('day',$options);
	}



#############################################################################
#############################################################################
# YEAR
	function Year($date = null, $format = 'Y')
	{
		return date($format, $this->get_stamp($date) );
	}
	
	function start_year($year = null)
	{
		$this->start_year = $year ? $year : $this->Year()-1;
	}
	
	function stop_year($offset = 6)
	{
		$this->stop_year = $this->Year()+$offset;
		if ($this->stop_year < date('Y'))
			$this->stop_year = date('Y')+$offset;
	}

	function select_year()
	{
		for ($y = $this->start_year; $y <= $this->stop_year; $y++)
		{
			$options .= "\t<option value='$y'";
			if (!$this->without_selected && $this->Year()==$y)
				$options .= " selected";
			$options .= ">$y</option>\n";
		}
		return $this->get_select('year',$options);
	}
	
	

#############################################################################
#############################################################################
# TIME
	function hour($date = null, $format = 'g')
	{
		return date($format, $this->get_stamp($date) );
	}
	
	function select_hour($format = 'g')
	{
		for ($h=0; $h < 12; $h++)
		{
			$options .= "\t<option value='$h'";
			if (!$this->without_selected)
			{
				if ($this->hour()==$h || ($this->hour()==12 && $h==0))
				$options .= " selected='1'";
			}
			$options .= ">".($h==0 ? 12 : $h)."</option>\n";
		}
		return $this->get_select('hour',$options);
	}

	function minute($date = null)
	{
		return date('i', $this->get_stamp($date) );
	}

	function select_minute($modulo = null)
	{
		for ($m = 0; $m <= 59; $m++)
		{
			if (!$modulo || ($modulo && ($m % $modulo==0)))
			{
				$options .= "\t<option value='$m'";
				if (!$this->without_selected && $this->minute()==$m)
					$options .= " selected='1'";
				$options .= ">:".$this->minute("12:$m:00",'i')."</option>\n";
			}
		}
		return $this->get_select('minute',$options);
	}
	
	function ampm($date = null)
	{
		return date('a', $this->get_stamp($date) );
	}

	function select_ampm()
	{
		for ($a=0; $a <= 12; $a+=12)
		{
			$options .= "\t<option value='$a'";
			if (!$this->without_selected && ($this->ampm()==$this->ampm("$a:00:00")))
				$options .= " selected='1'";
			$options .= ">".$this->ampm("$a:00:00")."</option>\n";
		}
		return $this->get_select('ampm',$options);
	}
}

?>
