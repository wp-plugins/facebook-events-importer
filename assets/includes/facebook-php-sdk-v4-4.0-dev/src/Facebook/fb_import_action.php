<?php	
require_once($_SERVER["DOCUMENT_ROOT"].'/wintersports/wp-load.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/wintersports/wp-content/themes/WWS/functions.php'); 
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
// Skip these two lines if you're using Composer
define('FACEBOOK_SDK_V4_SRC_DIR', 'facebook-php-sdk-v4-4.0-dev/src/Facebook/');
require __DIR__ .'/facebook-php-sdk-v4-4.0-dev/autoload.php';
require __DIR__ .'/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookSession.php';
require __DIR__ .'/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookRequest.php';
require __DIR__ .'/facebook-php-sdk-v4-4.0-dev/src/Facebook/GraphUser.php';
require __DIR__ .'/facebook-php-sdk-v4-4.0-dev/src/Facebook/FacebookRequestException.php';


use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

FacebookSession::setDefaultApplication('653128554814196','e9e5c7bcca7caadcafbec2f39e58202d');

$session = new FacebookSession('653128554814196|uHouX-xUSYWRDE8HPz8IzbneaSw');

$location = array(
"232337597065",
"ChristmasMountainVillage",
"granitepeak",
"skiTyrol",
"240731292664440",
"wilmotmtn",
"125248988199",
"brucemound",
"CAMP10SKIAREA",
"christiemountain",
"devilsheadresort",
"grandgeneva",
"CountyofTrails",
"LittleSwitzerland",
"minocquawinterpark",
"montdulac",
"mtlacrosse",
"mtashwabay",
"NordicMountain",
"PlymouthWisconsin",
"trollhaugen",
"SkiWhitecap",
"snowboardermag"
);

foreach ($location as $loc) {
$eventResponse = (new FacebookRequest($session, 'GET', '/'.$loc.'/events?fields=venue,location,cover,attending_count,description,end_time,id,name,owner,start_time,ticket_uri,timezone,is_date_only'))->execute()->getResponse();

$events = $eventResponse->data;

foreach ($events as $e) {
			/* import Events */
		$locData = (new FacebookRequest($session, 'GET', '/'.$loc.'?fields=website,phone'))->execute()->getResponse();
	    $event_id = $e->id;
	    
	

// args
$args = array(
    'numberposts' => -1,
    'post_type' => 'event',
    'meta_query' => array(
        array(
            'key' => 'facebook_event_id',
            'value' => $event_id,
        )
    )
);


$the_query = new WP_Query( $args );

 if( $the_query->have_posts() ){ }else{

		$post_information = array(
		'post_type' => 'event',
		'post_title' => wp_strip_all_tags($e->name),
		'post_content' => wp_strip_all_tags($e->description),
		'post_status' => 'publish'
				 );									 					 				
			
		$eId = wp_strip_all_tags($e->id);	
		$startDate = $e->start_time;		
		$endDate = $e->end_time;			
		$eventDate = date("m/d/y", strtotime($startDate));
		$eventTime = date("g:ia", strtotime($startDate));
		$endDate = date("m/d/y", strtotime($endDate));
		$endTime = date("g:ia", strtotime($endDate));
		$website = wp_strip_all_tags($locData->website);
	    $phone = wp_strip_all_tags($locData->phone);
		$city = wp_strip_all_tags($e->venue->city);
		$state = wp_strip_all_tags($e->venue->state);
		$street = wp_strip_all_tags($e->venue->street);
		$zip = wp_strip_all_tags($e->venue->zip);
		$venue = wp_strip_all_tags($e->location);
		$eventImage = wp_strip_all_tags($e->cover->source);
	 
		$post_id = wp_insert_post( $post_information );
		
		update_post_meta($post_id,'facebook_event_id', $eId);
		update_post_meta($post_id,'venue',$venue);
		update_post_meta($post_id,'venue_address',$street);
		update_post_meta($post_id,'venue_city',$city);
		update_post_meta($post_id,'venue_state',$state);
		update_post_meta($post_id,'venue_zip',$zip);
		update_post_meta($post_id,'event_starts',$eventDate);
		update_post_meta($post_id,'facebook_event_start_time',$eventTime);
		update_post_meta($post_id,'event_ends',$endDate);
		update_post_meta($post_id,'facebook_event_end_time',$endTime);
		update_post_meta($post_id,'event_website',$website);
		
		$url = $eventImage;
		$tmp = download_url( $url );
		$desc = "";
		$file_array = array();
	
		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;
	
		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}
	
		// do the validation and storage stuff
		$id = media_handle_sideload( $file_array, $post_id, $desc );
	
		// If error storing permanently, unlink
		if ( is_wp_error($id) ) {
			@unlink($file_array['tmp_name']);
			return $id;
		}
	
		$src = wp_get_attachment_url( $id );
										
					 
		}
 	}
}

?>