<?php

function wptouch_admin_do_callback() {
	global $_primed_setting;

	call_user_func( $_primed_setting->extra );
}