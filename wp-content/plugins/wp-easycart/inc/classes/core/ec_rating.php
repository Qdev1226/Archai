<?php
class ec_rating {

	public $review_count;
	public $product_rating;
	
	function __construct ( $rating_data ) {
		$this->review_count = 0;
		$this->product_rating = 0.0;
		
		if ( isset( $rating_data ) && is_array( $rating_data ) && 1 == count( $rating_data ) ) {
			$this->review_count = count( $rating_data );
			$total = 0;

			for ( $i = 0; $i < count( $rating_data ); $i++ ) {
				if ( isset( $rating_data[ $i ]['rating'] ) ) {
					$total = $total + $rating_data[$i]['rating'];
				}
			}

			$this->product_rating = ( $total / ( $this->review_count * 5 ) ) * 5;
		}
	}

	public function display_stars( $average = 0 ) {
		
		for ( $i = 0; $i < $average; $i++ ) {
			$this->display_star_on();
		}
		for ( $i = $average; $i < 5; $i++ ){
			$this->display_star_off();
		}
	}

	public function display_number_reviews() {
		return $this->review_count;
	}

	private function display_star_on() {
		echo '<div class="ec_product_star_on"></div>';
	}

	private function display_star_off() {
		echo '<div class="ec_product_star_off"></div>';
	}
}
