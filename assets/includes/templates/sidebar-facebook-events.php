<?php
$fbe800 = get_option("facebook_events_pro_version");  if($fbe800){
if ( ! is_active_sidebar( 'facebook-events' ) ) {
	return;
}
dynamic_sidebar( 'facebook-events' );
}else{	exit; }
 ?>