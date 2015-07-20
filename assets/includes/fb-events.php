<?php
add_image_size( 'fb_event_cover', '784', '295', true );
add_image_size( 'fb_event_list', '288', '192', true );
add_image_size( 'fb_event_ad', '288', '295', true );

add_action( 'init', 'create_fbe_post_event' );
add_theme_support( 'post-thumbnails' ); 

function create_fbe_post_event() {
  $slug = get_option("slug");
  if($slug == ""){$slug = 'facebook-events';}


  register_post_type( 'facebook_events',
    array(
      'labels' => array(
        'name' => __( 'Facebook Events' ),
        'singular_name' => __( 'Facebook Event' )
      ),
      'public' => true,
      'menu_position' => 5, 
      'menu_icon' =>  'dashicons-calendar', 
  	  'rewrite' => array('slug' => $slug),
  	  'supports' => array('title','editor','thumbnail','comments','tags'),
      //'taxonomies' => array('post_tag')
    )
  );

}


function fbe_add_meta_box() {

  $screens = array( 'facebook_events' );

  foreach ( $screens as $screen ) {

    add_meta_box(
      'facebook_events_sectionid',
      __( 'Facebook Event Fields', 'facebook_events_textdomain' ),
      'fbe_meta_box_callback',
      $screen,
      'normal',
      'high'
    );

  }
}

function fbe_feat_meta_box() {

  $screens = array( 'facebook_events' );

  foreach ( $screens as $screen ) {

    add_meta_box(
      'facebook_feature_eventid',
      __( 'Feature Facebook Event', 'facebook_events_textdomain' ),
      'fbe_feat_meta_box_callback',
      $screen,
      'side',
      'low'
    );
   

  }
}

add_action( 'add_meta_boxes', 'fbe_feat_meta_box' );
add_action( 'add_meta_boxes', 'fbe_add_meta_box' );


function fbe_feat_meta_box_callback( $post ) {
wp_nonce_field( 'fbe_feat_meta_box', 'fbe_feat_meta_box_nonce' );

$feature_event_value = get_post_meta($post->ID, 'feature_event', true);

if($feature_event_value == "yes"){ 
$field_id_checked = 'checked="checked"';
}else{
$field_id_checked = '';
}
echo '<label for="location">Feature this event</label>
      <input type="checkbox" name="feature_event" id="feature_event" value="yes" '.$field_id_checked.'/>';
}



function fbe_meta_box_callback( $post ) {
 
  wp_nonce_field( 'facebook_events_meta_box', 'facebook_events_meta_box_nonce' );

  $facebook_event_id = get_post_meta( $post->ID, 'facebook_event_id', true );
  $location = get_post_meta( $post->ID, 'location', true );
  $ticket_uri = get_post_meta( $post->ID, 'ticket_uri', true );
  $image_url = get_post_meta( $post->ID, 'image_url', true );
  $fb_event_uri = get_post_meta( $post->ID, 'fb_event_uri', true );
  $timezone = get_post_meta( $post->ID, 'event_timezone', true );

  $venue_phone = get_post_meta( $post->ID, 'venue_phone', true );
  $venue_email = get_post_meta( $post->ID, 'venue_email', true );
  $venue_website = get_post_meta( $post->ID, 'venue_website', true );
  $facebook_page = get_post_meta( $post->ID, 'facebook', true );
  $venue_desc = get_post_meta( $post->ID, 'venue_desc', true );
  $venue_name = get_post_meta( $post->ID, 'venue_name', true );
  $geo_latitude = get_post_meta( $post->ID, 'geo_latitude', true );
  $geo_longitude = get_post_meta( $post->ID, 'geo_longitude', true );
  $event_starts_sort_field = get_post_meta( $post->ID, 'event_starts_sort_field', true );

  $start_time = get_post_meta( $post->ID, 'start_time', true );
  $end_time = get_post_meta( $post->ID, 'end_time', true );

  $start_date = get_post_meta( $post->ID, 'event_starts', true );
  $end_date = get_post_meta( $post->ID, 'event_ends', true );

  date_default_timezone_set($timezone);
   if($start_date != ''){
   $start_date = strtotime($start_date);
   $start_date  = date('m/d/Y', $start_date);
   }else{ $start_date = '';}
   
   if($start_time != ''){
   $start_time = strtotime($start_time);
   $start_time = date('g:i a', $start_time);
   }else{$start_time ='';}   
   
   if($end_date != ''){
   $end_date = strtotime($end_date);
   $end_date = date('m/d/Y', $end_date);
    }else{ $end_date = '';}
  
   if($end_time != ''){
   $end_time = strtotime($end_time);
   $end_time = date('g:i a', $end_time);
    }else{$end_time = '';}



  echo '<div id="facebook_event_fields">';
  echo "<h4 style='margin:10px 0px; padding:10px 0px; color:#0074A2;'>Event Information</h4>";
  echo '<label for="location">Location</label>';
  echo '<input type="text" id="location" name="location" value="' . sanitize_text_field( $location ) . '" size="25" />';
  echo '<br />';
  echo '<label for="event_starts">Event Starts</label>';
  echo '<input type="text" id="event_starts" name="event_starts" value="'. sanitize_text_field( $start_date ) .'" size="10">@<input type="text" id="start_time" name="start_time" value="'. sanitize_text_field( $start_time ) .'"" size="8" />';
 
  $event_starts_sort_field = strtotime($start_date);
  $event_starts_sort_field = date("Y-m-d",$event_starts_sort_field);
  
  echo '<input type="hidden" id="event_starts_sort_field" name="event_starts_sort_field" value="'.sanitize_text_field( $event_starts_sort_field ).'" size="10">';
  echo '<br />';
  echo '<label for="event_ends">Event Ends</label>';
  echo '<input type="text" id="event_ends" name="event_ends" value="'. sanitize_text_field( $end_date ) .'" size="10">@<input type="text" id="end_time" name="end_time" value="'. sanitize_text_field( $end_time ) .'"" size="8" />';
  echo '<br />';
  echo '<label for="ticket_uri">Ticket URL</label>';
  echo '<input type="text" id="ticket_uri" name="ticket_uri" value="' . sanitize_text_field( $ticket_uri ) . '" size="45" />';
  echo '<br />';
  echo '<label for="fb_event_uri">Facebook Event Page</label>';
  echo '<input type="text" id="fb_event_uri" name="fb_event_uri" value="' . sanitize_text_field( $fb_event_uri ) . '" size="45" />';
  echo '<input type="hidden" id="facebook_event_id" name="facebook_event_id" value="' . sanitize_text_field( $facebook_event_id ) . '" size="25" />';
  echo '<input type="hidden" id="image_url" name="image_url" value="' . sanitize_text_field( $image_url ) . '" size="25" />';
  echo '<br />';
  echo "<h4 style='margin:10px 0px; padding:10px 0px; color:#0074A2;'>Venue Information</h4>";
  echo '<label for="venue_name">Venue Name</label>';
  echo '<input type="text" id="venue_name" name="venue_name" value="' . sanitize_text_field( $venue_name ) . '" size="45" />';
  echo '<br />';
  echo '<label for="phone">Phone</label>';
  echo '<input type="text" id="venue_phone" name="venue_phone" value="' . sanitize_text_field( $venue_phone ) . '" size="45" />';
  echo '<br />';
  echo '<label for="email">Email</label>';
  echo '<input type="text" id="venue_email" name="venue_email" value="' . sanitize_text_field( $venue_email ) . '" size="45" />';
  echo '<br />';
  echo '<label for="website">Website </label>';
  echo '<input type="text" id="venue_website" name="venue_website" value="' . sanitize_text_field( $venue_website ) . '" size="45" />';
  echo '<br />';
  echo '<label for="facebook_page">Facebook</label>';
  echo '<input type="text" id="facebook_page" name="facebook_page" value="' . sanitize_text_field( $facebook_page ) . '" size="45" />';
  echo '<br />';
  echo '<label for="geo_latitude">Geo Latitude</label>';
  echo '<input type="text" id="geo_latitude" name="geo_latitude" value="' . sanitize_text_field( $geo_latitude) . '" size="45" />';
  echo '<br />';
  echo '<label for="geo_longitude">Geo Longitude</label>';
  echo '<input type="text" id="geo_longitude" name="geo_longitude" value="' . sanitize_text_field( $geo_longitude ) . '" size="45" />';
  echo '<br />';
  echo '<label for="venue_desc">Venue About</label><br /><br />';
  echo '<textarea rows="15" cols="50" id="venue_desc" name="venue_desc" class="widefat" style="width:100%!important; max-width:540px!important; max-height:100px!important;" />'. esc_textarea($venue_desc ) .'</textarea>';
  echo '<br />';
  echo '<br />';
  echo '</div>';

}



function fbe_save_meta_box_data( $post_id ) {

  if ( ! isset( $_POST['facebook_events_meta_box_nonce'] ) ) {
    return;
  }

  if ( ! wp_verify_nonce( $_POST['facebook_events_meta_box_nonce'], 'facebook_events_meta_box' ) ) {
    return;
  }

    if ( ! isset( $_POST['fbe_feat_meta_box_nonce'] ) ) {
    return;
  }

  if ( ! wp_verify_nonce( $_POST['fbe_feat_meta_box_nonce'], 'fbe_feat_meta_box' ) ) {
    return;
  }
 
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  if ( isset( $_POST['facebook_events'] ) && 'page' == $_POST['facebook_events'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
      return;
    }

  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }
  }

if( isset( $_POST[ 'feature_event' ] ) ) {
      update_post_meta( $post_id, 'feature_event', 'yes' );
  } else {
      update_post_meta( $post_id, 'feature_event', 'no' );
  }

  $fields = "location,ticket_uri,facebook_event_id,image_url,start_time,end_time,event_starts,event_starts_sort_field,event_ends,fb_event_uri,venue_phone,venue_email,venue_website,facebook_page,facebook,venue_name,venue_desc,geo_latitude,geo_longitude";
  $post_meta= array_filter(explode(",",$fields));

  foreach ($post_meta as $meta){

  if ( ! isset( $_POST[$meta] ) ) {
    return;
  }
  $meta_value = sanitize_text_field( $_POST[$meta] );
  update_post_meta( $post_id, $meta, $meta_value );
  }


}
add_action( 'save_post', 'fbe_save_meta_box_data' );


function get_fbe_field($meta){
return get_post_meta( get_the_ID(), $meta, true );
}

function fbe_field($meta){
echo get_post_meta( get_the_ID(), $meta, true );
}

function get_fbe_date($meta,$format){
  $event_date = get_post_meta( get_the_ID(), $meta, true );
  $timezone = get_post_meta( get_the_ID(),'event_timezone', true );
  if($event_date){
  if($timezone){
  date_default_timezone_set($timezone);
  }
  $fbdate = strtotime($event_date);
  return date($format, $fbdate);
  }
}


function get_fbe_image($size){
  $image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $size );
  $url = $image['0'];
  return $url;
}

function fbe_image($size){
  $image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $size );
  $url = $image['0'];
  echo $url;
}

?>