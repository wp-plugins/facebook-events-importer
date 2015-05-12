<?php
/*
 * Plugin Name: Facebook Events Importer
 * Plugin URI: http://wpfbevents.com/
 * Description: A simple way to import Facebook events. 
 * Version: 2.3.3
 * Author: <a href="http://volk.io/">Volk</a>
 * Author URI: http://volk.io/
  * License: GPL2
 /*  Copyright 2015  Volk  (email : media@volk.io)

    This program is free software; You can modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 add_action( 'admin_init', 'wpfbe_php' );   
 register_activation_hook(__FILE__,'wpfbe_php'); 
 register_activation_hook(__FILE__,'fbe_install_msg'); 
 register_deactivation_hook( __FILE__, 'fbe_remove_msg' );


function wpfbe_php() {
	
// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
if ( strnatcmp(phpversion(),'5.4.0') >= 0 ){ } else {
	deactivate_plugins( plugin_basename( __FILE__ ) );
	wp_die( 'This plugin requires PHP Version 5.4. Your current version is '. phpversion() );}
	
	}	 

function hide_fbe_free_notice() {
 if (get_option('fbe_msg_shown') == "yes"){  remove_action( 'admin_notices', 'fbe_free_admin_notice' ); }
 if (get_option('facebook_events_pro_deal') == "remove"){  remove_action( 'admin_notices', 'fbe_offer_admin_notice' ); } 

}
add_action( 'admin_head', 'hide_fbe_free_notice');
add_action( 'admin_notices', 'fbe_offer_admin_notice' );


function fbe_remove_msg() {
	update_option('facebook_events_free','removed');
	update_option('fbe_msg_shown','no');
	update_option('pro_msg_shown','no');
} 

function fbe_install_msg() {
wpfbe_php();	
update_option('facebook_events_free','installed');
}

add_action( 'admin_notices', 'fbe_free_admin_notice' );

  


function fbe_offer_admin_notice() {	}	


  function fbe_free_admin_notice() {
		  	$fbe800 = get_option("facebook_events_pro_version");  if($fbe800){update_option('fbe_msg_shown','yes');}else{
		    ?>
		    <div class="updated"  style="padding:20px">
		        <b>YOU ROCK!</b> Now upgrade to PRO and be even more awesome, <a href="http://wpfbevents.com/"><b>upgrade now!</b></a>
		    </div>
		    <?php
		    update_option('fbe_msg_shown','yes');
    	}
	}	

   function fbe_app_data_callback(){
		 $app_id = sanitize_text_field($_POST["app_id"]);
		 $app_secret = sanitize_text_field( $_POST["app_secret"]);
		 update_option("app_id", $app_id);
         update_option("app_secret", $app_secret);
         fbe_validate_session(get_option("app_id"),get_option("app_secret"));
         die();
	}
  
  	function setup_fbe_import_admin_menu() {
		add_submenu_page('options-general.php','Facebook Events Setup', 'Facebook Events', 'manage_options','facebook_events_import', 'fbe_import_settings');
	}
	
    add_filter("plugin_action_links_facebook_events_import/facebook_events_importer.php", 'fbe_settings' );
  	
   function fbe_settings($links) { 
	   $settings_link = admin_url('options-general.php'); 
	   array_unshift($links, $settings_link); 
	   return $links; 
   }


		
  function import_fbe_scripts() {
	  if ( is_admin() ) {
	  wp_enqueue_style( 'fbe_style', plugins_url( '/assets/css/fb_import.css', __FILE__ ) );	
	  wp_register_script('fbe_import', plugins_url( '/assets/js/facebook_import.js', __FILE__ ), array('jquery'), '1.0.0');
	  wp_localize_script('fbe_import', 'fbeAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));	
	  wp_enqueue_script('jquery-ui-datepicker');
      wp_enqueue_style('jquery-style-ui', plugins_url( '/assets/css/jquery-ui.css', __FILE__) );
      wp_enqueue_script( 'fbe_import' );
 		}
    }
  
  function fbe_callback(){

        $facebook_page = sanitize_text_field($_POST["page"]);
        $facebook_pages = get_option("facebook_pages");
        if ($facebook_page == "") {
        echo '<div class="error" style="color:#222222; font-weight:700; font-size:1em; padding:10px">You have to enter something.</div>';
        die();
        }
        if (strpos($facebook_pages,$facebook_page) !== false) {
         echo '<div class="error" style="color:#222222; font-weight:700; font-size:1em; padding:10px">'.$facebook_page.' already exists.</div>';
        }else{ 
        fbe_facebook_sdk($facebook_page,get_option("app_id"),get_option("app_secret"));
		$facebook_pages = sanitize_text_field($_POST["pages"].','.$facebook_page);
    	update_option("facebook_page", $facebook_page);
        update_option("facebook_pages", $facebook_pages);
		}

        
		die();
   }


  function fbe_remove_callback(){	   
        $facebook_page = sanitize_text_field($_POST["page"]);
		$facebook_pages = str_replace($facebook_page,"",get_option("facebook_pages"));
        update_option("facebook_pages", $facebook_pages);
		die();
  }

  function fbe_update_callback(){	   
        $facebook_page = sanitize_text_field($_POST["page"]);
        fbe_facebook_sdk($facebook_page,get_option("app_id"),get_option("app_secret"));
        update_option("facebook_page", $facebook_page);
		die();
  }


function fbe_import_settings(){
?>	

	
	<?php echo '<div id="wfei_plugin_head"><img class="full-width" src="' . plugins_url( 'assets/images/wfei.svg', __FILE__ ) . '" ></div>'; ?>
	<h3>Facebook App Settings</h3>
	<p>You will need a Facebook App ID and App Secret to import events. <a href="https://developers.facebook.com/apps/">Get App ID &amp; App Secret</a></p>
	 <form id="facebook_app" method="post" >
		 <label for="app_id">App ID</label>
		 <input type="text" id="app_id" name="app_id" value="<?php echo get_option("app_id"); ?>">
		 <label for="app_secret">App Secret</label>
		 <input id="app_secret" name="app_secret" type="text" value="<?php echo get_option("app_secret"); ?>" />
		 <input type="submit" value="Save App Settings" class="button-secondary"/>  
	 </form>
	 <div id="appdata_results"></div>
	 <br/>
	 <hr />

	<!-- PAID FEATURES --> 
	 <h3><span class="pro">PRO</span> Settings</h3> 
	 <?php $fbe800 = get_option("facebook_events_pro_version");  if($fbe800){

  	$slug = get_option("slug");
	if($slug == ""){$slug = 'facebook-events';}
   	
   	$fbe_per_page = get_option("fbe_posts_per_page");
   	if($fbe_per_page == ""){$fbe_per_page = 10;}

   	$fbe_geo_map = get_option("fbe_geo_map");
   	$fbe_venue = get_option("fbe_venue");

	$wpfbe_primary_color = get_option('wpfbe_primary_color');
				if ($wpfbe_primary_color == '') {
				$wpfbe_primary_color = '#0075A2';
	}
	$wpfbe_secondary_color = get_option('wpfbe_secondary_color');
				if ($wpfbe_secondary_color == '') {
				$wpfbe_secondary_color = '#222222';
	} 
	$wpfbe_tertiary_color = get_option('wpfbe_tertiary_color');
				if ($wpfbe_tertiary_color == '') {
				$wpfbe_tertiary_color = '#939597';
	} 
	$wpfbe_inverted_color = get_option('wpfbe_inverted_color');
				if ($wpfbe_inverted_color == '') {
				$wpfbe_inverted_color = '#fff';
	} 

   ?>
   <div id="plugin_settings"></div>
   <form id="wfei_plugin_settings" method="post" >
	<b> Set your events page slug: </b> <a href="<?php echo site_url(); ?>/<?php echo $slug; ?>"><?php echo site_url(); ?>/<?php echo $slug; ?></a></br/>
	<label for="slug"><span style="color:#99999E;"><i><?php echo site_url(); ?>/</i></span></label>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </label>
	<input type="text" id="slug" name="slug" value="<?php echo $slug; ?>"> 			
	

	<p>
	<input type="checkbox" id="fbe_geo_map" name="fbe_geo_map" <?php if($fbe_geo_map != 'false'){echo 'checked';} ?> />
	
	<label for="fbe_geo_map"><b>Display Google Map</b></label>
	<br /><span style="color:#99999E;"><i>Displays Google Map on single events page</i></span>
	</p>
	<p>
	<input type="checkbox" id="fbe_venue" name="fbe_venue" <?php if($fbe_venue != 'false'){echo 'checked';} ?> />

	<label for="fbe_venue"><b>Display Venue Details</b></label>
	<br /><span style="color:#99999E;"><i>Displays onsingle events page</i></span>
	</p>
	<p>
	<label for="fbe_posts_per_page"><b>Posts per page</b></label>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          </label>
		<input type="text" size="5" id="fbe_posts_per_page" name="fbe_posts_per_page" value="<?php echo $fbe_per_page; ?>">
		<br /> <span style="color:#99999E;"><i>Enter <b>"all"</b> to display all.</i></span>
	</p>

<div class="group"></div>

		<h3>Style Editor</h3>
		<div id="wpfbe_style_editor">

		<div class="wpfbe_style_picker">	
		<label for="wpfbe_primary_color"><b>Primary Color</b><br/> Dates, links, feat event button</label> <br/>
		<input type="text" value="<?php echo $wpfbe_primary_color; ?>" name="wpfbe_primary_color"  id="wpfbe_primary_color" class="wpfbe-color-field" />
		</div>

		<div class="wpfbe_style_picker">	
		<label for="wpfbe_secondary_color"><b>Secondary Color</b> <br/>Text color, event highlight</label> <br/>
		<input type="text" value="<?php echo $wpfbe_secondary_color; ?>" name="wpfbe_secondary_color"  id="wpfbe_secondary_color" class="wpfbe-color-field" />
		</div>

		<div class="wpfbe_style_picker">	
		<label for="wpfbe_tertiary_color"><b>Tertiary Color</b> <br/>Event location</label> <br/>
		<input type="text" value="<?php echo $wpfbe_tertiary_color; ?>" name="wpfbe_tertiary_color"  id="wpfbe_tertiary_color" class="wpfbe-color-field" />
		</div>

		<div class="wpfbe_style_picker">	
		<label for="wpfbe_inverted_color"><b>Inverted Color</b> <br/> Inverted text </label> <br/>
		<input type="text" value="<?php echo $wpfbe_inverted_color; ?>" name="wpfbe_inverted_color"  id="wpfbe_inverted_color" class="wpfbe-color-field" />
		</div>		

	<div class="group"></div>		
	</div>		
	<div class="group"></div>
	<br />
	<input type="submit" value="Save Settings" class="button-primary"/> 
	<img class="loader" src="<?php echo plugins_url( 'assets/images/X-loader.gif', __FILE__ ); ?>"> 
    </form>


	 <?php }else{ ?> 
	   <div class="updated"  style="padding:10px; margin:0px 0px 10px 0px; border-color:#28a9e1;">
		<span style="font-weight:900;"><img src="http://wpfbevents.com/wp-content/themes/wpfbevents/assets/images/twitter_bird.png" style="margin-right:10px; position:relative; top:5px; " /><a style="color:#28a9e1;" href="http://wpfbevents.com/" target="_blank">Pay with a tweet </a>to upgrade to PRO version!</span> 
      </div>
	 More settings and options available when you <b><a href="http://wpfbevents.com/">upgrade to the <i>PRO</i> version</a></b>.
	 <?php } ?>
	 <br />
	 You can also create your own templates. <b><a href="http://wpfbevents.com/code-examples/">View code examples</a></b>.
     <hr />
	<!-- END PAID FEATURES --> 

	 <div id="wfei_events_wrap">
	 <h3>Import Events</h3> 
	<p> Enter a Facebook page id or page name that you want to import events from.</p>
	<form id="facebook_event_import" method="post" >
	        <textarea id="facebook_pages" class="hidden" name="facebook_pages" rows="24" cols="50"><?php echo get_option("facebook_pages"); ?></textarea>
	        <input type="text" id="facebook_page" name="facebook_page">
	        <input type="hidden" id="facebook_page_updated" name="facebook_page_updated">
	        <input type="hidden" name="update_settings" value="Y" />
	        <input type="submit" value="Import Page Events" class="button-primary"/> 
	        <img class="loader" src="<?php echo plugins_url( 'assets/images/X-loader.gif', __FILE__ ); ?>" width="30" heigh="30"> 
	</form>
	        <div id="event_results_loading"></div> 
	        <div id="event_results"></div>
	        <br /> 
	   	 <hr />
	<h3>Imported Pages</h3>
	<p>We'll fetch events for you automaticly but you can reload at anytime. Deleting does <b>not</b> remove events previously imported but will remove them from update queue.</p>

	 <?php 
	 $pages = get_option("facebook_pages");
	 $location = array_filter(explode(",",$pages));
     $liq = 0;
	foreach ($location as $loc){
		$liq++;
	  }

	if($liq >= 1){
	if(get_option('fbe_cron_date') == ''){
	    $fbe_last = current_time('timestamp');
	}else{
		$fbe_last = get_option('fbe_cron_date');
	}
    
    $fbe_current_time = current_time('timestamp');

	echo '<i style="color:#222222; font-size:12px; ">Automaticly updated: <b style="color:#0075A2;">'. human_time_diff( $fbe_last, $fbe_current_time ) . ' ago</b></i>';
	}

	 ?>
	<ul>
	<?php
	$pages = get_option("facebook_pages");
	$location = array_filter(explode(",",$pages));

	foreach ($location as $loc){	
	if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9.]+$/', $loc))
			{
			 echo '<li class="fb_event_page"><a href="https://facebook.com/'.$loc.'">' .$loc. '</a> <span class="fetch" data-id="'.$loc.'" style="background-image: url('. plugins_url( '/assets/images/reload_events.svg', __FILE__ ).');">Fetch</span><span class="remove" data-id="'.$loc.'" style="background-image:url('.plugins_url( '/assets/images/delete_this.svg', __FILE__ ).');">remove</span></li>';
			}
			else
			{
			    //invalid
			}	
	}

	?>
	</ul>
	</div>
<?php } 


/* PRO Features */



 
   function fbe_pro_page_id(){
	  return get_option('fbe_pro_page_id');
   }
	

function fbe_pro_template( $page_template ){
	$id = fbe_pro_page_id();

	    if ( is_page( $id ) ) {
	    
	        $page_template = dirname( __FILE__ ) . '/assets/includes/templates/facebook-events-template.php';
	   
    }
    return $page_template;
}


    
function get_fbe_custom_post_type_template($single_template) {
	
     global $post;

     if ($post->post_type == 'facebook_events') {
     
          $single_template = dirname( __FILE__ ) . '/assets/includes/templates/single-facebook_events.php';
      
     }
     return $single_template;
}


function load_fbe_callback(){
$page = sanitize_text_field($_POST["page"]);	
$max = get_option("fbe_posts_per_page");
if($max=='all'){$max = -1;}
get_fbe_events($max,$page);
die();
	}
	
function get_fbe_events($max,$page){
 global $post;

$paged = (get_query_var('paged')) ? get_query_var('paged') : $page;


		$currentdate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

		$args = array (
                  	'meta_query'=> array(
	                    array(
	                      'key' => 'event_starts_sort_field',
	                      'compare' => '>',
	                      'value' => $currentdate,
	                      'type' => 'DATE',
	                    )),
		    'post_type' => 'facebook_events',
			'posts_per_page' => $max,
			'paged' => $paged,
	        'meta_key' => 'event_starts_sort_field',
            'orderby' => 'meta_value',
			'order' => 'ASC'
			
		);
	
		
		$fbe_query = new WP_Query( $args );
		 if( $fbe_query->have_posts() ): 
		$maxPages = $fbe_query->max_num_pages;	
	     echo '<div id="maxPages" data-id='.$maxPages.'></div>';
		while ( $fbe_query->have_posts() ) : $fbe_query->the_post();
		  $event_title = get_the_title();
		  $event_desc =  get_the_content();
		  $event_image = get_fbe_image('cover');
		  $event_starts_month = get_fbe_date('event_starts','M');
		  $event_starts_day = get_fbe_date('event_starts','j');
		  $location = get_fbe_field('location');
		  $permalink = get_permalink();
		  $featured = get_post_meta($post->ID, 'feature_event', true);
	?>

   
	  <div class="fbecol-1-3">
	  <div class="fbecol" data-id="<?php echo $permalink; ?>">	
	  <div class="fbe_list_image" style="background-image:url(<?php echo get_fbe_image('cover'); ?>);" >	  
	  <div class="fbe_list_bar">
	  <div class="fbe_list_date">
	  	<div class="fbe_list_month"><?php echo $event_starts_month; ?></div>
		<div class="fbe_list_day"><?php echo $event_starts_day; ?></div>	
	  </div>	
	  <div class="fbe_col_title"><h2><?php echo limitFBETxt( $event_title,30); ?></h2></div>
	  <div class="fbe_col_location"><h4><?php echo limitFBETxt($location,40); ?></h4></div>
	  </div>	
	  </div>
	  </div>  
	  </div> 
	<?php
	     endwhile;     		
	wp_reset_postdata(); 
		 endif;

}

 

function wfei_plugin_settings_callback(){

     $slug = str_replace(" ","-", sanitize_text_field($_POST["slug"]));
     $fbe_geo_map = sanitize_text_field($_POST["fbe_geo_map"]);
     $fbe_venue  = sanitize_text_field($_POST["fbe_venue"]);
	 $fbe_per_page = sanitize_text_field($_POST["fbe_posts_per_page"]);
     update_option("fbe_posts_per_page", $fbe_per_page );
   	 update_option("fbe_geo_map", $fbe_geo_map);
     update_option("fbe_venue", $fbe_venue  );

     update_option("wpfbe_primary_color", sanitize_text_field($_POST["wpfbe_primary_color"]));
     update_option("wpfbe_secondary_color", sanitize_text_field($_POST["wpfbe_secondary_color"]));
     update_option("wpfbe_tertiary_color", sanitize_text_field($_POST["wpfbe_tertiary_color"]));
     update_option("wpfbe_inverted_color", sanitize_text_field($_POST["wpfbe_inverted_color"]));


     $page_id = fbe_pro_page_id();

		     if($slug != ''){
			 update_option("slug", $slug);
		     }else{ 
		     echo'<div class="error" style="color:#222222; font-weight:700; font-size:1em; padding:10px">Page slug required</div>';
		     }
		     echo '<div class="updated" style="color:#222222; font-weight:700; font-size:1em; padding:10px">Settings Saved</div>'; 

		die();	
}

 
   function limitFBETxt($content,$limit){

	$content = preg_replace("/<img[^>]+\>/i", "", $content); 
	$content = strip_tags($content); 
	$content = strip_shortcodes( $content );
	
	if (strlen($content) > $limit) {
	$stringCut = substr($content, 0, $limit);
	$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
	return $string;
	}else{
		return $content;
		}	
   }

/* FACEBOOK EVENTS WIDGET  & SIDEBAR */



function fbe_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Facebook Events Sidebar', 'facebook-events' ),
        'id' => 'facebook-events',
        'description' => __( 'Widgets in this area will be shown on facebook events.', 'facebook-events' ),
        'before_widget' => '<div class="fbe-widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2><hr />',
    ) );
}


class fbe_widget extends WP_Widget {


	function __construct() {
		parent::__construct(
			'fbe_widget', 
			__( 'Facebook Events widget', 'text_domain' ), 
			array( 'description' => __( 'Show Facebook events in your sidebar', 'text_domain' ), ) 
		);
	}

	
	function widget( $args, $instance ) {
		
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}


		$disp_posts = apply_filters( 'post_count', $instance['disp_posts'] );

        $today = current_time('m/d/Y');
        $oneYear= date('m/d/Y', strtotime('+ 365 day'));


$currentdate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

		$args = array (
                  	'meta_query'=> array(
	                    array(
	                      'key' => 'event_starts_sort_field',
	                      'compare' => '>',
	                      'value' => $currentdate,
	                      'type' => 'DATE',
	                    )),
		    'post_type' => 'facebook_events',
		    'posts_per_page' => $disp_posts,
	        'meta_key' => 'event_starts_sort_field',
            'orderby' => 'meta_value',
			'order' => 'ASC'
			
		);



	
		
		$fbe_query = new WP_Query( $args );
		 if( $fbe_query->have_posts() ): 
		$maxPages = $fbe_query->max_num_pages;	
	     echo '<div id="maxPages" data-id='.$maxPages.'></div>';
		while ( $fbe_query->have_posts() ) : $fbe_query->the_post();
		  $event_title = get_the_title();
		  $event_desc =  get_the_content();
		  $event_image = get_fbe_image('cover');
		  $event_starts_month = get_fbe_date('event_starts','M');
		  $event_starts_day = get_fbe_date('event_starts','j');
		  $location = get_fbe_field('location');
		  $permalink = get_permalink();
		  $featured = get_post_meta('feature_event', true);
	?>

   
	  <div class="fbecol-1-1">
	  <div class="fbe-sidebar-post" data-id="<?php echo $permalink; ?>">	
	  <div class="fbe_list_bar">
	  <div class="fbe_list_date">
	  	<div class="fbe_list_month"><?php echo $event_starts_month; ?></div>
		<div class="fbe_list_day"><?php echo $event_starts_day; ?></div>	
	  </div>	
	  <div class="fbe_col_title"><h2><?php echo limitFBETxt( $event_title,35); ?></h2></div>
	  <div class="fbe_col_location"><h4><?php echo limitFBETxt($location,100); ?></h4></div>
	  </div>	
	 
	  </div>  
	  </div> 
	<?php
	     endwhile;
		 endif;
		
	wp_reset_query();  

	}


	function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Upcoming Events', 'text_domain' );
		$disp_posts= ! empty( $instance['disp_posts'] ) ? $instance['disp_posts'] : __( '3', 'text_domain' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		<br />
		<label for="<?php echo $this->get_field_id( 'disp_posts' ); ?>"><?php _e( 'Posts to Display:' ); ?></label> <br />
		<input class="shortfat" size="8" id="<?php echo $this->get_field_id( 'disp_posts' ); ?>" name="<?php echo $this->get_field_name( 'disp_posts' ); ?>" type="text" value="<?php echo esc_attr( $disp_posts ); ?>">
		</p>
		<?php 
	}


	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['disp_posts'] = ( ! empty( $new_instance['disp_posts'] ) ) ? strip_tags( $new_instance['disp_posts'] ) : '';
		return $instance;
	}

} 


function register_fbe_widget() {
    register_widget( 'fbe_widget' );
}



  function import_fbe_PRO_scripts() {
    wp_register_script('fbe_pro_import', plugins_url( '/assets/js/facebook_events_pro.js', __FILE__ ), array('jquery'), '1.0.0');
    wp_localize_script('fbe_pro_import', 'fbeAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));	
	wp_enqueue_script( 'fbe_pro_import' );
    wp_enqueue_style( 'fbe_pro_style', plugins_url( '/assets/css/facebook_events_pro.css', __FILE__ ) );
    wp_register_script('fbe_map', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
    wp_enqueue_script( 'fbe_map','','',false  ); 
     if( is_admin() ) {      
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker');
    }
    }

function fbe_event_map() {
if(is_singular( 'facebook_events' )) {
	if(get_option('fbe_geo_map') == 'true'){ 
	$LatLng = get_fbe_field('geo_latitude').','.get_fbe_field('geo_longitude');
    echo '
	<script>
	var event_loc = new google.maps.LatLng('.$LatLng.');
	var marker;
    var fbe_map;
	
	function initialize(){
	 var mapOptions = {
     zoom: 16,
     scrollwheel: false,
     navigationControl: false,
     scaleControl: false,
     draggable: false,
     center: event_loc,
     panControl: false,
     mapTypeControl: false,
     zoomControl: true,
     zoomControlOptions: {
     	style: google.maps.ZoomControlStyle.SMALL,
     	position: google.maps.ControlPosition.RIGHT_BOTTOM
    	}
	  };	

	fbe_map = new google.maps.Map(document.getElementById("fbe_map_canvas"),mapOptions);

	marker = new google.maps.Marker({
    map:fbe_map,
    animation: google.maps.Animation.DROP,
    position: event_loc
 	});

	}
	
		
	google.maps.event.addDomListener(window,"load",initialize); 
	</script>';
		}
	}
}

add_action('wp_head', 'fbe_event_map');



$fbe800 = get_option("facebook_events_pro_version");  if($fbe800){
add_action( 'widgets_init', 'fbe_widgets_init' );	
add_action( 'widgets_init', 'register_fbe_widget' );
add_action( 'init', 'import_fbe_PRO_scripts' );
add_filter( 'single_template', 'get_fbe_custom_post_type_template' );
add_action( 'wp_ajax_load_facebook_events', 'load_fbe_callback' );
add_action( 'wp_ajax_nopriv_load_facebook_events', 'load_fbe_callback' );
add_filter( 'page_template', 'fbe_pro_template' );
add_action( 'wp_ajax_wfei_plugin_settings', 'wfei_plugin_settings_callback' );
add_action( 'wp_ajax_nopriv_wfei_plugin_settings', 'wfei_plugin_settings_callback' );
}
/* END PRO */

require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php'); 
require('assets/includes/fb-events.php');
require('assets/includes/fb-sch.php');
require('assets/includes/fb_import_action.php');


add_action( 'wp_ajax_facebook_events_request', 'fbe_callback' );
add_action( 'wp_ajax_nopriv_facebook_events_request', 'fbe_callback' );

add_action( 'wp_ajax_facebook_events_update', 'fbe_update_callback' );
add_action( 'wp_ajax_nopriv_facebook_events_update', 'fbe_update_callback' );

add_action( 'wp_ajax_facebook_events_remove', 'fbe_remove_callback' );
add_action( 'wp_ajax_nopriv_facebook_events_remove', 'fbe_remove_callback' );

add_action( 'wp_ajax_facebook_app_data', 'fbe_app_data_callback' );
add_action( 'wp_ajax_nopriv_facebook_app_data', 'fbe_app_data_callback' );

add_action( 'init', 'import_fbe_scripts' );

add_action("admin_menu", "setup_fbe_import_admin_menu");

function wpfb_feed_request($qv) {
	if (isset($qv['feed']))
		$qv['post_type'] = 'facebook-events';
	return $qv;
}
add_filter('request', 'wpfb_feed_request');



function custom_fbe_post_nav($current_event_date) {
$currentdate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$args = array (
                  	'meta_query'=> array(
	                    array(
	                      'key' => 'event_starts_sort_field',
	                      'compare' => '>',
	                      'value' => $current_event_date,
	                      'type' => 'DATE',
	                    ),
	                     array(
	                      'key' => 'event_starts_sort_field',
	                      'compare' => '>',
	                      'value' => $currentdate,
	                      'type' => 'DATE',
	                    ),


	                    ),
		    'post_type' => 'facebook_events',
	        'meta_key' => 'event_starts_sort_field',
            'orderby' => 'meta_value',
			'order' => 'ASC',
			'posts_per_page' => -1
			
		);
	
		$pages = array();
		$fbe_nav_query = get_posts($args);
		
		foreach ($fbe_nav_query as $fbe_nav_post) {
			  $pages[] += $fbe_nav_post->ID;
		}
		 $id = get_the_id();
	$current = array_search($id, $pages);
    $prevID = $pages[$current-1];
    $nextID = $pages[$current+1];

    $total = count($pages);

    foreach ($pages as $mykey => $myval) {
        if ($myval== $id) {
            $key = ($mykey + 1);
            }
    }

     if (!empty($prevID)) {
        echo '<a class="prev_fb_event" rel="prev" href="'.get_permalink($prevID).'" title="'.get_the_title($prevID).'"><span class="arrow-left"></span>Previous Event </a>';
    } else {
         echo  '<a class="prev_fb_event" rel="prev" href="'.get_permalink(end($pages)).'" title="'.get_the_title(end($pages)).'"><span class="arrow-left"></span>Previous Event </a>';
    }


     if (!empty($nextID)) {
        echo '<a class="next_fb_event" rel="next" href="'.get_permalink($nextID).'" title="'.get_the_title($nextID).'">Next Event <span class="arrow-right"></span></a>';
    } else {
         echo  '<a class="next_fb_event" rel="next" href="'.get_permalink(array_shift($pages)).'" title="'.get_the_title(array_shift($pages)).'">Next Event <span class="arrow-right"></span></a>';
    }
		 	

}

function getaddress($lat,$lng){
$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
$json = @file_get_contents($url);
$data=json_decode($json);
$status = $data->status;
if($status=="OK")
return $data->results[0]->formatted_address;
else
return false;
}



class wpfbe_customize {

public static function wpfbe_header_output() {
	      ?>
	      <!-- Facebook Events CSS--> 
	      <style type="text/css">
	           <?php self::wpfbe_generate_css('.fbe_list_date,.fbe_feat_event_link', 'background-color', 'wpfbe_primary_color', '',''); ?>
	           <?php self::wpfbe_generate_css('#load_more_fbe,.fbe-facebook-css > div ', 'background-color', 'wpfbe_secondary_color', '',''); ?>

	           
	           <?php self::wpfbe_generate_css('.fbe_col_location h4', 'color', 'wpfbe_tertiary_color', '',''); ?>
	           <?php self::wpfbe_generate_css('.fbe_list_date,.fbe_feat_event_link,#load_more_fbe', 'color', 'wpfbe_inverted_color', '',''); ?>
	           <?php self::wpfbe_generate_css('.prev_fb_event,.next_fb_event,#fbe_sidebar a', 'color', 'wpfbe_primary_color', '',''); ?>
	           <?php self::wpfbe_generate_css('.prev_fb_event:hover,.next_fb_event:hover', 'color', 'wpfbe_inverted_color', '',''); ?>
	           <?php self::wpfbe_generate_css('.prev_fb_event:hover,.next_fb_event:hover,#event_facebook_page', 'background-color', 'wpfbe_primary_color', '',''); ?>
	           <?php self::wpfbe_generate_css('#event_facebook_page', 'fill', 'wpfbe_inverted_color', '',''); ?>




<?php echo '

@-webkit-keyframes fbehover{
  0%{}
  100%{background-color:'.get_option('wpfbe_secondary_color').'; color:'.get_option('wpfbe_inverted_color').';}
}
@-moz-keyframes fbehover{
  0%{}
  100%{background-color:'.get_option('wpfbe_secondary_color').'; color:'.get_option('wpfbe_inverted_color').';}
}
@-o-keyframes fbehover{
  0%{}
  100%{background-color:'.get_option('wpfbe_secondary_color').'; color:'.get_option('wpfbe_inverted_color').';}
}
@keyframes fbehover{
  0%{}
  100%{background-color:'.get_option('wpfbe_secondary_color').'; color:'.get_option('wpfbe_inverted_color').';}
}
@-webkit-keyframes fbehoverOut{
  0%{background-color:'.get_option('wpfbe_secondary_color').';}
  100%{ background-color:rgba(255,255,255,0.9); }
}@-moz-keyframes fbehoverOut{
  0%{background-color:'.get_option('wpfbe_secondary_color').';}
  100%{ background-color:rgba(255,255,255,0.9); }
}@-o-keyframes fbehoverOut{
  0%{background-color:'.get_option('wpfbe_secondary_color').';}
  100%{ background-color:rgba(255,255,255,0.9); }
}@keyframes fbehoverOut{
  0%{background-color:'.get_option('wpfbe_secondary_color').';}
  100%{ background-color:rgba(255,255,255,0.9); }
}
';	?>			

	      </style> 
	      <!--/  Facebook Events CSS--> 
	      <?php
	   }

public static function wpfbe_generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_option($mod_name);

      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }	   
}
add_action( 'wp_head' , array( 'wpfbe_customize' , 'wpfbe_header_output' ) );

?>