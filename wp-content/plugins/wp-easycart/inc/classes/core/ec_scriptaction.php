<?php

class ec_scriptaction{
	
	private $current_page_id;									// INT
	private $store_page_id;										// INT
	private $cart_page_id;										// INT
	private $account_page_id;									// INT
	private $request_method;									// STRING (POST OR GET)
	private $ec_action;											// STRING
	
	function __construct($current_page_id, $store_page_id, $cart_page_id, $account_page_id, $request_method, $ec_action){
		$this->current_page_id = $current_page_id;
		$this->store_page_id = $store_page_id;
		$this->cart_page_id = $cart_page_id;
		$this->account_page_id = $account_page_id;
		$this->request_method = $request_method;
		$this->ec_action = $ec_action;
	}
	
	public function process_action(){
		
		if( $this->is_post_or_get() )							$this->process_helper();
		
	}
	
	private function is_post_or_get(){
		
		if( $_SERVER['REQUEST_METHOD'] == "POST" || $_SERVER['REQUEST_METHOD'] == "GET" )
																return true;
		else													return false;
	
	}
	
	private function process_helper(){
		
		if($this->current_page_id == $this->cart_page_id) 		
																$this->process_cart_action();
		else if($this->current_page_id == $this->account_page_id) 	
																$this->process_account_action();
		else if($this->current_page_id == $this->store_page_id) 
																$this->process_store_action();
		
	}
	
	private function process_cart_action(){
		if($this->ec_action == "addtocart")						include("ec_cartscripts/addtocart.php");
		else if($this->ec_action == "updatecartitem")			include("ec_cartscripts/updatecartitem.php");
		else if($this->ec_action == "removecartitem")			include("ec_cartscripts/removecartitem.php");
		else if($this->ec_action == "guestcheckout")			include("ec_cartscripts/guest_checkout.php");
		else if($this->ec_action == "login")					include("ec_cartscripts/loginuser.php");
		else if($this->ec_action == "submitorder")				include("ec_cartscripts/submitorder.php");
		else if($this->ec_action == "signout")					include("ec_cartscripts/signout.php");
		else if($this->ec_action == "confirmorder"){
			set_form_session_vars();
			header("location:".$cartpage.$permalinkdivider."confirmorder=true");
		
		}
	}
	
	private function process_account_action(){
		if($this->ec_action == "billinginfo")					include("ec_accountscripts/update_billinginfo.php");
		else if($this->ec_action == "editpassword")				include("ec_accountscripts/update_password.php");
		else if($this->ec_action == "forgotpassword")			include("ec_accountscripts/retrievepassword.php");
		else if($this->ec_action == "login")					include("ec_accountscripts/loginuser.php");
		else if($this->ec_action == "personalinfo")				include("ec_accountscripts/update_personal_info.php");
		else if($this->ec_action == "register")					include("ec_accountscripts/register.php");
		else if($this->ec_action == "shippinginfo")				include("ec_accountscripts/update_shippinginfo.php");
		else if($this->ec_action == "signout")					include("ec_accountscripts/signout.php");
	}
	
	private function process_store_action(){
		if($this->ec_action == "submitreview")					include("ec_store/product_details/submit_review.php");
	}
	
}

?>