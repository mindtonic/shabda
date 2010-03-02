<?php

class *default* extends Model #implements ImageInterface
{
	// Sub Objects
	public $image;

	// Validations
	protected $required_fields = array();
	protected $field_lengths = array();
	protected $unique_fields = array();

	// Accesible Attributes
	protected $accesor_attributes = array();

	// Basic Model Associations
	protected $associations = array();

	protected function custom_validations() {}

	protected function before_save() {}

	// Formatting
	protected $titleize = array();
	protected function custom_formatting() {}

  public function ignore_form_fields()
  {
    return array();
	}
}

?>
