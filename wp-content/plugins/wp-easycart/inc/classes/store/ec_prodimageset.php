<?php

class ec_prodimageset {
	public $product_id;
	public $optionitem_id;
	public $image1;
	public $image2;
	public $image3;
	public $image4;
	public $image5;
	public $product_images;

	function __construct( $product_id, $image_data ) {
		$this->product_id = $product_id;
		$this->optionitem_id = $image_data->optionitem_id;
		$this->image1 = $image_data->image1;
		$this->image2 = $image_data->image2;
		$this->image3 = $image_data->image3;
		$this->image4 = $image_data->image4;
		$this->image5 = $image_data->image5;
		$this->product_images = $image_data->product_images;
	}
}
