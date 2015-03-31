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
		  $event_start_time = get_fbe_date('event_starts','g:i a');
		  $event_end_date = get_fbe_date('event_ends','M j, Y');
		  $event_end_time = get_fbe_date('event_ends','g:ia');

		  $location = get_fbe_field('location');
		  $permalink = get_permalink();
?>       
<div class="fbegrid fbegrid-pad">
	<div class="col-8-12">
	<div class="fbe_single_event_image_wrap">
	<img class="fbe-full-width" src="<?php echo get_fbe_image('full'); ?>" /> 
	</div>	
	<hr />	
	<h1 class="fbe_single_title"> <? echo $event_title; ?></h1>
	<div id="fbe_single_date"><? echo $event_start_date; if($event_start_time){echo ' @ '.$event_start_time; } ?>
    <? if($event_end_date){echo '&nbsp;&mdash;&nbsp;&nbsp;'. $event_end_date; } if($event_end_time){echo ' @ '.$event_end_time; } ?>
	</div>
	<p><? echo $event_desc ; ?></p>
	<? if ( comments_open() || get_comments_number() ) { ?>
   <div id="comments">
	<hr />
   <div class="fbe_comments">Post a comment</div>  
   <? comments_template(); ?>
   </div>  
<?  } ?>
	</div>
	<div class="col-4-12">
		<div id="fbe_sidebar">
		<h2>Event Details</h2>
		<hr />		
		<ul>
		<li><b>Location</b><br/><? echo $location ; ?></li>
		<? if ($fb_link){?>
		<li><b>Facebook Event</b><br/><a href="<? echo $fb_link; ?>" target="_blank">View event on Facebook</a></li>
		<li><b>Admission</b><br/><a href="<? echo $tickets; ?>" target="_blank">Get Tickets</a></li>

		<? } ?>
	    </ul>
	    <?php load_template ( dirname( __FILE__ ) . '/sidebar-facebook-events.php' ) ; ?>
		</div>
	</div>

</div>
<?  endwhile;

endif;
wp_reset_query();  
?>



<?php get_footer(); ?>