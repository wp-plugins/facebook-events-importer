<?
add_action( 'wp', 'fbe_cron_setup_schedule' );

function fbe_cron_setup_schedule() {
	if ( ! wp_next_scheduled( 'fbe_cron_hourly_event' ) ) {
		wp_schedule_event( time(), 'hourly', 'fbe_cron_hourly_event');
	}
}


add_action( 'fbe_cron_hourly_event', 'fbe_cron_do_this_hourly' );

function fbe_cron_do_this_hourly() {
	// Check for new events every hour
	$pages = get_option("facebook_pages");
	$location = array_filter(explode(",",$pages));

	foreach ($location as $loc){
		fbe_facebook_sdk($loc,get_option("app_id"),get_option("app_secret"));
	}
	update_option('fbe_cron_date', time());
}
?>