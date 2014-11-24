<?php 
function tidy_coords($latlng) {
	$one = str_replace("(", "", $latlng);
	$two = str_replace(")", "", $one);
	$coords = explode(",", $two);
	
	return $coords;
}
	$args = array(
		'post_type' => 'wheretobuy',
		'posts_per_page' => -1
	);
	query_posts($args);
	$x = 0;
	if ( have_posts() ) : while ( have_posts() ) : the_post(); 
		
		$coords = tidy_coords(get_post_meta(get_the_ID(), 'martygeocoderlatlng', true));
		$address_one = get_post_meta(get_the_ID(), 'emily_meta_address_one', true);
		$address_two = get_post_meta(get_the_ID(), 'emily_meta_address_two', true);
		$city = get_post_meta(get_the_ID(), 'emily_meta_address_city', true);
		$state = get_post_meta(get_the_ID(), 'emily_meta_state', true);
		$phone = get_post_meta(get_the_ID(), 'emily_meta_phone', true);
		$web = get_post_meta(get_the_ID(), 'emily_meta_website', true);
		$post_code = strtoupper(get_post_meta(get_the_ID(), 'martygeocoderaddress', true));
		$lat = $coords[0];
		$lng = $coords[1];
		
		$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
		$image_a = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail');
		$image = $image_a[0];
		
		$meta = get_post_meta(get_the_ID());
		
		$output[$x]['id'] = get_the_ID(); 
		$output[$x]['name'] = get_the_title();
		$output[$x]['lat'] = $lat;
		$output[$x]['lng'] = $lng;
		$output[$x]['address'] = $address_one;
		$output[$x]['address2'] = $address_two;
		$output[$x]['city'] = $city;
		$output[$x]['state'] = $state;
		$output[$x]['postal'] = $post_code;
		$output[$x]['phone'] = $phone;
		$output[$x]['web'] = $web;
		$output[$x]['image'] = $image;
	
	
	/*	
		foreach ($meta as $key => $value):
			$output[$x]['meta'][$key] = $value;
		endforeach;
		
	 {
        "id":"19",
        "name":"Chipotle Chanhassen",
        "lat":"44.858736",
        "lng":"-93.533661",
        "address":"560 W 79th",
        "address2":"",
        "city":"Chanhassen",
        "state":"MN",
        "postal":"55317",
        "phone":"952-294-0301",
        "web":"http:\/\/www.chipotle.com",
        "hours1":"Mon-Sun 11am-10pm",
        "hours2":"",
        "hours3":""
    },*/
	
	/*
	?>
	<!-- Begin Post <?php the_ID(); ?> -->
	<div class="row" id="post-<?php the_ID(); ?>">
		<div class="the_post col-md-12">
			<div class="padded-and-bordered">
			<?php if (!is_single()) : ?>
				<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php endif; ?>
				<div class="post-content">
				<?php (is_single() ? the_content() : the_excerpt() ); ?>
				</div>
				<div class="post-footer row">
					<div class="col-sm-3">
					<a href="<?php the_permalink(); ?>"><span class="badge"><i class="fa fa-thumb-tack"></i> Posted <?php the_time("jS F Y"); ?></span></a>
					</div>
					
					<div class="col-sm-9">
					<?php $tags = @get_the_terms(get_the_ID(), array('category', 'post_tag')); ?>	
	
					<div class="pull-right"><?php echo list_all_tags($tags); ?></div>   
					</div>      
				</div>
			</div>
		</div>
	</div>
	<!-- End Post <?php the_ID(); ?> -->
<?php

*/
$x++;
 endwhile; endif;
// echo "<pre>";
//	var_dump($output);
//	echo "</pre>";
header('Content-Type: application/json');
echo json_encode($output);
	?>