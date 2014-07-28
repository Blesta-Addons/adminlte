<?php
/**
 * Facebook Login for Blesta
 * @author Joshua Richard <josh.richard@gmail.com>
 * @license http://theyconfuse.me/license/apache2
 */

/** @see https://github.com/facebook/facebook-php-sdk */
require_once("./facebook.php");

/** EDIT: URL to Blesta install */
    $blesta_url = "https://domain.com/blesta";
    
/** EDIT: Blesta Shared Login Key */
    $key = "LOGIN_KEY";
    
/** EDIT: Redirect URL after successful login */
    $r = "$blesta_url/client/";
    
/** EDIT: Redirect URL if no such account exists */
    $not_found = "$blesta_url";
    
/**
 * EDIT: Facebook App API Information
 * @see https://developers.facebook.com/apps
 */
    $facebook = new Facebook (
        array (
            'appId'  => 'APP_ID',
            'secret' => 'APP_SECRET',
        )
    );

/** Done editing */
    
if($facebook->getUser()) {
    $user_profile = $facebook->api('/me','GET');
    $t = time();
    $u = $user_profile['email'];
    $h = hash_hmac("sha256", $t . $u . $r, $key);

    if( file_get_contents("$blesta_url/plugin/shared_login/?" . http_build_query(compact("t", "u", "r", "h"))) > '' ) {
        header("Location: $blesta_url/plugin/shared_login/?" . http_build_query(compact("t", "u", "r", "h")));
    }
    else {
        header("Location: $not_found");
    }
}

else {
    header("Location: " . $facebook->getLoginUrl( array( 'scope' => 'email' ) ));
}

?>