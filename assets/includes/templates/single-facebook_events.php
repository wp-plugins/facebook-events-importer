<?php get_header(); ?>
<? if (have_posts()) :
$fbe800 = get_option("facebook_events_pro_version");  if($fbe800){  }else{ exit; }
   while (have_posts()) :
      the_post();
          $fb_link = get_fbe_field('fb_event_uri'); 
          $tickets = get_fbe_field('ticket_uri'); 
          $event_title = get_the_title();
		  $event_desc =  get_the_content();
		  $event_image = get_fbe_image('cover');
		  $event_start_date = get_fbe_date('event_starts','M j, Y');
		  $event_start_time = get_fbe_date('start_time','g:i a');
		  $event_end_date = get_fbe_date('event_ends','M j, Y');
		  $event_end_time = get_fbe_date('end_time','g:ia');
          $LatLng = get_fbe_field('geo_latitude').','.get_fbe_field('geo_longitude');
		  $location = get_fbe_field('location');
		  $permalink = get_permalink();
	      $venue_phone = get_fbe_field( 'venue_phone');
	      $venue_email = get_fbe_field( 'venue_email');
	      $venue_website = get_fbe_field( 'venue_website');
	      $facebook_page = get_fbe_field( 'facebook');
	      $venue_desc = get_fbe_field( 'venue_desc');
	      $venue_name = get_fbe_field( 'venue_name');
	      $venue_email = get_fbe_field( 'venue_email');
	      $geo_latitude = get_fbe_field( 'geo_latitude');
	      $geo_longitude = get_fbe_field( 'geo_longitude');
	      $event_image = get_fbe_image('full'); 
?> 

<div class="group"></div>      
<div class="fbegrid fbegrid-pad">
	<div class="fbecol-8-12">
	<? if ($event_image ){ ?>
	<div class="fbe_single_event_image_wrap">
	<img class="fbe-full-width" src="<?php echo $event_image; ?>" /> 
	</div>	
	<hr />	
	<? } ?>
	<h1 class="fbe_single_title"> <? echo $event_title; ?></h1>
	<div id="fbe_single_date"><? echo $event_start_date; if($event_start_time){echo ' @ '.$event_start_time; } ?>
    <? if($event_end_date){echo '&nbsp;&mdash;&nbsp;&nbsp;'. $event_end_date; } if($event_end_time){echo ' @ '.$event_end_time; } ?>
	</div>
	<p><? echo $event_desc ; ?></p>
    <?php //the_tags( '<div id="fbe_tags">', '', '</div>' ); ?> 
	<? if(get_option('fbe_geo_map') == 'true'){ 
		if ($LatLng !=','){

		echo '<div class="group"></div><hr/>';

		if($venue_name){
			echo '<br/><h3>'.$venue_name.'</h3>';
		}


		if($venue_name != $location){echo $location; }else{
			$address = getaddress(get_fbe_field('geo_latitude'),get_fbe_field('geo_longitude'));
			if($address)
			{
			echo $address;
			}
			else
			{
			}			
		}
		
		echo '</br>';		
		echo '<div id="fbe_map_canvas" style="min-height:500px;"></div>'; 
		}
	} 
	?>
<hr />
<div class="group"></div>

		<?php custom_fbe_post_nav(get_fbe_date('event_starts','m/d/Y')); ?>
<div class="group"></div>
<hr />

	<? if ( comments_open() || get_comments_number() ) { ?>
	<div id="comments">
	<div class="fbe_comments">Post a comment</div>  
	<? comments_template(); ?>
	</div>  
	<?  } ?>

	</div>
	<div class="fbecol-4-12">
		<div id="fbe_sidebar">
		<? if(get_option('fbe_venue') == 'true'){ ?>
		<? if($venue_name){ ?>
		<h2>Event Venue</h2>
		<hr />	
		<ul>
		<? if($venue_name){ ?><li><b>Venue </b><br/><? echo $venue_name; ?></li><? } ?>
		<? if($venue_desc){ ?><li><b>About</b><br/><? echo $venue_desc; ?></li><? } ?>
		<? if($location){
		if($location == $venue_name){
		$address = getaddress(get_fbe_field('geo_latitude'),get_fbe_field('geo_longitude'));
			if($address)
			{
			echo '<li><b>Location</b><br/>'.$address,'</li>';
			}
			else
			{
				echo '<li><b>Location</b><br/>'.$location,'</li>';
			}
		}else{
			$address = getaddress(get_fbe_field('geo_latitude'),get_fbe_field('geo_longitude'));
			if($address)
			{
			echo '<li><b>Location</b><br/>'.$address,'</li>';
			}
		}	
		?>
		<? } ?>
		<? if($venue_website){ ?><li><b>Website </b><br/><? echo '<a href="'.$venue_website.'" target="_blank">'.$venue_website.'</a>'; ?></li><? } ?>
		<? if($venue_phone){ ?><li><b>Phone</b><br/><? echo $venue_phone; ?></li><? } ?>
		<? if($venue_email){ ?><li><b>Email</b><br/><? echo $venue_email; ?></li><? } ?>
		<? if($facebook_page){ ?><li><b>Follow On</b><br/><a class="event_facebook_page" href="<? echo $facebook_page; ?>" target="_blank">
		<svg version="1.1" id="event_facebook_page" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="241.6 0 308.3 612" enable-background="new 241.6 0 308.3 612" xml:space="preserve">
<path  d="M549.9,218.6H439.7V165c0,0-6.9-49.5,30.2-49.5c41.3,0,73,0,73,0V1.2H417.8c0,0-103.2,0-103.2,103.2
	c0,23.4,0,64.7,0,114.2h-73v90.8h73c0,140.3,0,300,0,300h125.3V310.8h83.9L549.9,218.6z"/>
<path d="M2016.6,177.7c-18.4,8.7-35.6,11.9-57.3,15.1c20.5-11.9,35.6-30.2,42.1-54c-18.4,11.9-38.9,18.4-62.6,23.7
	c-18.4-18.4-45.4-30.2-72.3-30.2c-50.8,0-96.1,45.4-96.1,99.3c0,8.7,0,15.1,3.2,20.5c-81-3.2-155.5-42.1-204.1-101.5
	c-8.7,15.1-11.9,30.2-11.9,50.8c0,33.5,18.4,62.6,45.4,81c-15.1,0-30.2-6.5-45.4-11.9l0,0c0,47.5,33.5,87.5,77.7,96.1
	c-8.7,3.2-18.4,3.2-27,3.2c-6.5,0-11.9,0-18.4-3.2c11.9,38.9,47.5,69.1,92.9,69.1c-33.5,27-74.5,42.1-123.1,42.1
	c-8.7,0-15.1,0-23.7,0c45.4,27,96.1,45.4,150.1,45.4c180.3,0,278.6-150.1,278.6-278.6v-11.9C1986.3,217.7,2004.7,199.3,2016.6,177.7
	z"/>
</svg></a></li><? } ?>
	
		</ul>
		<? } ?>
		<? } ?>
		<h2>Event Details</h2>
		<hr />		
		<ul>
	<? if(get_option('fbe_venue') == 'false'){ ?>

		<? if($location){
		if($location == $venue_name){
		$address = getaddress(get_fbe_field('geo_latitude'),get_fbe_field('geo_longitude'));
			if($address)
			{
			echo '<li><b>Location</b><br/>'.$address,'</li>';
			}
			else
			{
				echo '<li><b>Location</b><br/>'.$location,'</li>';
			}
		}else{
			$address = getaddress(get_fbe_field('geo_latitude'),get_fbe_field('geo_longitude'));
			if($address)
			{
			echo '<li><b>Location</b><br/>'.$address,'</li>';
			}
		}	
		?>
		<? } ?>
	<? } ?>
	<? if($event_start_date){?><li><b>Starts</b><br/><? echo $event_start_date; if($event_start_time){echo ' @ '.$event_start_time; } ?></li><? } ?>
    <? if($event_end_date){?><li><b>Ends</b><br/> <? echo $event_end_date; if($event_end_time){echo ' @ '.$event_end_time; } ?></li><? } ?>
		<? if ($fb_link){?><li><b>Facebook Event</b><br/><a href="<? echo $fb_link; ?>" target="_blank">View event on Facebook</a></li><? } ?>
		<? if($tickets){?><li><b>Admission</b><br/><a href="<? echo $tickets; ?>" target="_blank">Get Tickets</a></li><? } ?>
	    </ul>
	    <?php load_template ( dirname( __FILE__ ) . '/sidebar-facebook-events.php' ) ; ?>
		</div>
	</div>

</div>
<?  endwhile;

endif;
wp_reset_query();  
?>

<div class="group"></div> 

<?php get_footer(); ?>