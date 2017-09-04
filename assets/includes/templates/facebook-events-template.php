<?php
/**
 * Template Name: Facebook Events
 * Description: Facebook Events Template
 */
 ?>
<?php get_header(); ?>
<?php $fbe800 = get_option("facebook_events_pro_version");  if($fbe800){  }else{ exit; } ?>
<div class="fbegrid" style="display:none;">
<h1 class="fbe_page_title"><?php _e( get_the_title()); ?></h1>	
    <div class="fbe_page_desc">
	<?php 
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post(); 
			the_content();
		} 
	}
	?>
    </div>
</div>   
<?php
     $feat_post_count = 0; 
     $post_count = 0;
    /* $currentdate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
		$args = array (
		    'post_type' => 'facebook_events',
			'posts_per_page' => -1,
			'orderby'=> 'modified',
            'order' => 'DESC',
            );
	*/
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
           'orderby'=> 'modified',
            'order' => 'DESC',
			
		);




		$fbe_query = new WP_Query( $args );
		if( $fbe_query->have_posts() ): 
		while ( $fbe_query->have_posts() ) : $fbe_query->the_post();

		  $eventType = '';	
		  $event_title = get_the_title();
		  $event_desc =  get_the_content();
		  $event_image = get_fbe_image('cover');
		  $event_starts_month = get_fbe_date('event_starts','M');
		  $event_starts_day = get_fbe_date('event_starts','j');
		  $location = get_fbe_field('location');
		  $permalink = get_permalink();
		  $featured = get_post_meta($post->ID, 'feature_event', true);
		  $post_count++;
		  if($featured == 'yes'){
		  	$feat_post_count++;
		  }
         if($feat_post_count == 1 && $featured == 'yes'){ 
	?>
      <div class="group">
      <div id="fbe_header">
      <div class="fbegrid fbegrid-pad fbe_feat_col">
			
	  <div class="fbecol-1-2">
	  <div class="fbecol" data-id="<?php echo $permalink; ?>">	
	  <div class="featured fbe_list_image" style="background-image:url(<?php echo get_fbe_image('cover'); ?>);" >	  
	  <div class="featured fbe_list_bar" style="background:transparent!important;">
	  <div class="fbe_list_date">
	  	<div class="fbe_list_month"><?php echo $event_starts_month; ?></div>
		<div class="fbe_list_day"><?php echo $event_starts_day; ?></div>	
	  </div>	
	  </div>	
	  </div>
	  </div>  
	  </div> 
	  <div class="fbecol-1-2 fbe-hide-on-mobile">
	  <h1 class="fbe_page_title"><?php _e( $event_title ); ?></h1>
	  <div class="featured_fbe_col_location"><h4><?php if ($location){ _e($location ); }?></h4></div>	
	  <div class="fbe_feat_event_desc"><?_e( limitFBETxt($event_desc,200) );?></div>
	  <a href="<?php echo $permalink; ?>" class="fbe_feat_event_link">View Event</a>	
	  </div>
	  </div>
	  <div class="fbe_featured_image" style="background-image:url(<?php echo get_fbe_image('cover'); ?>);" ></div> 
      </div>
      </div>
      <?php
      }
	     endwhile;
		 endif;
			
	wp_reset_query();  

 ?>

 <div class="fbegrid fbegrid-pad">
  <div class='fbe-facebook-css' style='-webkit-transform:scale(0.27)'> <div></div><div></div><div></div></div>
 
 <div id="facebook_events_wrap">

 </div> 
 </div>
  <div class="fbegrid fbegrid-pad"> 
   <div class="col-1-1"> 
   <?php if(get_option("fbe_posts_per_page") != 'all'){	
 	_e( '<div id="load_more_fbe" data-id="1"></div>');
	}
	?>	
</div>
</div>
<?php get_footer(); ?>