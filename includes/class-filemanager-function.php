<?php

function set_userid_cookie() {
	if(!get_current_user_id()) {
        $cookie_name = 'userid';
        if(!isset($_COOKIE[$cookie_name])) {
            $cookie_value = intval(rand(10000, 99999));
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30)); // 86400 = 1 day
        }
	}
}
add_action( 'init', 'set_userid_cookie');

?>