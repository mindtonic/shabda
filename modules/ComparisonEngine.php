<?php

class ComparisonEngine
{
	public $document1;
	public $document2;
	private $positive;
	private $negative;
	private $line_return = "||==||";

	function __construct($document1, $document2)
	{
		// Set value of two documents for comparison
		$this->document1 = $this->split_text($document1);
		$this->document2 = $this->split_text($document2);
		// Compute Difference
		$this->difference();
		// Assemble Positive Output
		$this->assemble_positive();
		$this->polish_positive();
    // Assemble Negative Output
		$this->assemble_negative();
		$this->polish_negative();
	}

	###########################################################################
	
	private function split_text($string)
	{
		return preg_split('/\s/', $string);
	}
	
	private function difference()
	{
		$this->positive = array_diff($this->document1, $this->document2);
		$this->negative = array_diff($this->document2, $this->document1);
	}
	
	###########################################################################
	
	private function assemble_positive()
	{
	  $output = "";
		foreach ($this->document1 as $key => $value)
		{
			if (array_key_exists($key, $this->positive) && $value)
			  $value = '<span class="positive">'.$value.'</span>';
			$output .= ($value ? $value." " : "\r\n\r\n");
		}
		$this->document1 = $output;
	}
	
	private function polish_positive()
	{
		$this->document1 = ereg_replace('</span> <span class="positive">', ' ', $this->document1);
	}
	
	public function document1()
	{
		return $this->output_positive;
	}

	###########################################################################
	
	private function assemble_negative()
	{
	  $output = "";
		foreach ($this->document2 as $key => $value)
		{
			if (array_key_exists($key, $this->negative))
			  $value = '<span class="negative">'.$value.'</span>';
			$output .= ($value ? $value." " : "\r\n\r\n");
		}
		$this->document2 = $output;
	}
	
	private function polish_negative()
	{
		$this->document2 = ereg_replace('</span> <span class="negative">', ' ', $this->document2);
	}
	
	public function document2()
	{
		return $this->output_negative;
	}

	###########################################################################

}

?>
