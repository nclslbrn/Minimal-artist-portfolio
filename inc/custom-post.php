<?php
function create_post_type() {
  register_post_type( 'project',
    array(
      'labels' => array(
        'name'               => _x( 'Projects', 'post type general name', 'Minimal-Artistic-Portfolio' ),
        'singular_name'      => _x( 'Project', 'post type singular name', 'Minimal-Artistic-Portfolio' ),
        'menu_name'          => __( 'Projects', 'Minimal-Artistic-Portfolio' ),
        'name_admin_bar'     => __( 'Project', 'Minimal-Artistic-Portfolio' ),
        'add_new'            => __( 'Add New', 'Minimal-Artistic-Portfolio' ),
        'add_new_item'       => __( 'Add New Project', 'Minimal-Artistic-Portfolio' ),
        'new_item'           => __( 'New Project', 'Minimal-Artistic-Portfolio' ),
        'edit_item'          => __( 'Edit Project', 'Minimal-Artistic-Portfolio' ),
        'view_item'          => __( 'View Project', 'Minimal-Artistic-Portfolio' ),
        'all_items'          => __( 'All Projects', 'Minimal-Artistic-Portfolio' ),
        'search_items'       => __( 'Search Projects', 'Minimal-Artistic-Portfolio' ),
        'parent_item_colon'  => __( 'Parent Projects:', 'Minimal-Artistic-Portfolio' ),
        'not_found'          => __( 'No projects found.', 'Minimal-Artistic-Portfolio' ),
        'not_found_in_trash' => __( 'No projects found in Trash.', 'Minimal-Artistic-Portfolio' )
      ),
      'can_export' => TRUE,
      'public' => TRUE,
      'exclude_from_search' => FALSE,
      'query_var' => true,
      'publicly_queryable' => TRUE,
      '_builtin' => false,
      'capability_type' => 'post',
      'rewrite' => array('slug' => 'project','with_front' => FALSE),
      'has_archive' => TRUE,
      'hierarchical' => FALSE,
      'menu_position' => 5,
      'menu_icon' => 'dashicons-lightbulb',
      'show_in_menu' => TRUE,
      'show_in_nav_menus' => TRUE,
      'supports' => array('title', 'editor', 'revisions', 'thumbnail' ),
      'taxonomies' => array( 'category','post_tag')
    )
  );
  register_post_type( 'event',
    array(
      'labels' => array(
        'name'               => _x( 'Events', 'post type general name', 'Minimal-Artistic-Portfolio' ),
        'singular_name'      => _x( 'Event', 'post type singular name', 'Minimal-Artistic-Portfolio' ),
        'menu_name'          => __( 'Events', 'Minimal-Artistic-Portfolio' ),
        'name_admin_bar'     => __( 'Event', 'Minimal-Artistic-Portfolio' ),
        'add_new'            => __( 'Add New', 'Minimal-Artistic-Portfolio' ),
        'add_new_item'       => __( 'Add New Event', 'Minimal-Artistic-Portfolio' ),
        'new_item'           => __( 'New Event', 'Minimal-Artistic-Portfolio' ),
        'edit_item'          => __( 'Edit Event', 'Minimal-Artistic-Portfolio' ),
        'view_item'          => __( 'View Event', 'Minimal-Artistic-Portfolio' ),
        'all_items'          => __( 'All Events', 'Minimal-Artistic-Portfolio' ),
        'search_items'       => __( 'Search Events', 'Minimal-Artistic-Portfolio' ),
        'parent_item_colon'  => __( 'Parent Events:', 'Minimal-Artistic-Portfolio' ),
        'not_found'          => __( 'No events found.', 'Minimal-Artistic-Portfolio' ),
        'not_found_in_trash' => __( 'No events found in Trash.', 'Minimal-Artistic-Portfolio' )
      ),
      'can_export' => TRUE,
      'public' => TRUE,
      'exclude_from_search' => FALSE,
      'query_var' => true,
      'publicly_queryable' => TRUE,
      '_builtin' => false,
      'capability_type' => 'post',
      'rewrite' => array('slug' => 'event','with_front' => FALSE),
      'has_archive' => TRUE,
      'hierarchical' => FALSE,
      'menu_position' => 5,
      'menu_icon' => 'dashicons-calendar',
      'show_in_menu' => TRUE,
      'show_in_nav_menus' => TRUE,
      'supports' => array('title', 'editor', 'revisions', 'thumbnail' ),
      'taxonomies' => array( 'category','post_tag')
    )
  );
register_taxonomy( __('category', 'Minimal-Artistic-Portfolio'), array('post', 'page', 'project', 'event'), array( 'hierarchical' => true, 'label' => 'Category', 'query_var' => true, 'rewrite' => true ) );
register_taxonomy('post_tag', array('post', 'page', 'project', 'event') );

}
add_filter( 'pre_get_posts', 'show_custom_posts' );

/*--------------CUSTOM-POST-ON-HOME--------------*/
function show_custom_posts( $query )
{
    if ( (is_home() && $query->is_main_query() ) || is_feed() )
    $query->set( 'post_type', array( 'post', 'page', 'project', 'event') );
    return $query;
}

add_action( 'init', 'create_post_type' );

/*--------------CUSTOM-POST-MENU--------------*/

function list_content_type()
{
    $post_type = get_post_types( array('public' => true) );
    return $post_type;
}

function list_custom_posts( $type, $limit)
{
    $project_query = new WP_Query( array ( 'post_type' => $type, 'post_status' => 'publish', 'posts_per_page' => $limit) );


    if($project_query->have_posts()) :

      while($project_query->have_posts()) :

        global $post;
        $project_query->the_post();

        get_template_part( 'template-parts/content', 'project' );

      endwhile;

  endif;
  //wp_reset_postdata();

}
function get_event($message)
{
    global $wp_query;
    $events = get_post_custom($wp_query->post->ID);
    if(!empty($events['event'][0])){
        ?>
        <p class="event-of-this-project"><?php _e('Events', 'Minimal-Artistic-Portfolio'); ?>:</p><ul class="related-events">
        <?php $eventQuery = explode(',', $events['event'][0] ); ?>
        <?php foreach ($eventQuery as $event)
        {
          $title = get_the_title( $event );
          $url = get_permalink( $event );
            ?>
            <li><a href="<?= $url ?>"><?= $title ?></a></li>
            <?php
        }
        ?></ul><?php
    }
}
function get_project($eventId, $message)
{

  global $wpdb;
  $projectQuery = $wpdb->get_results("SELECT post_id, meta_value
                                      FROM $wpdb->postmeta
                                      WHERE meta_key = 'event'", ARRAY_A );
    if(!empty($projectQuery))
    {
      ?><p class="list-title"><?php _e('Projects', 'Minimal-Artistic-Portfolio'); ?>:</p><ul class="related-projects"><?php
      $returnedProject = array();
      foreach($projectQuery as $project)
      {
        $returnedProject['id'] = $project['post_id'];
        $returnedProject['savedEvent'] = explode(',', $project['meta_value']);

        if(in_array($eventId, $returnedProject['savedEvent']))
        {
          $title = get_the_title($returnedProject['id']);
          $url = get_permalink($returnedProject['id']);
          ?>
          <li><a href="<?= $url ?>"><?= $title ?></a></li>
          <?php
        }else{
          ?>
          <?php
        }
      }
    }
    wp_reset_postdata();
}
function list_custom_posts_by_date( $type, $limit ) {

  $postYear = "";
  $currentPostID = get_the_ID();
  $postYear = date_i18n("Y", strtotime(get_post_meta( $currentPostID, 'BEGINDATE', true)));

  wp_reset_postdata();

  $args = array(  'post_type' => $type,
                  'posts_per_page' => $limit,
                  'meta_key' => 'BEGINDATE',
                  'orderby' => 'meta_value',
                  'order' => 'DESC',
                  'meta_query' => array(
                    array(
                      'key' => 'BEGINDATE',
                      'compare' => 'LIKE'
                        )
                      )
                    );
  $posts = new WP_Query( $args );

  if($posts->have_posts()){

    $postsByYear = array();
    $mapData = array();
    $counter = 0;
    while( $posts->have_posts() ) : $posts->the_post();

      $cptId = get_the_ID();

      $year = date_i18n("Y", strtotime(get_post_meta( $cptId, 'BEGINDATE', true)));

      $postsByYear[$year][$cptId] = array(
                                    'id'        => $cptId,
                                    'title'     => get_the_title( $cptId ),
                                    'link'      => get_permalink( $cptId ),
                                    'place'     => get_post_meta( $cptId, 'PLACE', true),
                                    'content'   => get_the_content(),
                                    'beginDate' => date_i18n('j F  Y', strtotime(get_post_meta( $cptId, 'BEGINDATE', 'true') ) ),
                                    'endDate'   => date_i18n('j F Y', strtotime(get_post_meta( $cptId, 'ENDDATE', 'true') ) ),
                                    'thumbnail' => get_the_post_thumbnail( $cptId, 'carton')
                                  );


      $mapData[$counter] = array (  'info' => '<a href="'.get_permalink( $cptId ).'">'.get_the_title( $cptId ).'</a>',
                                    'latt' => get_post_meta( $cptId, 'LATT', true),
                                    'long' => get_post_meta( $cptId, 'LONG', true)
                                  );
      $counter++;
    endwhile;
    wp_reset_postdata();

    if(!empty($postsByYear)) {

       if(empty($postYear)) {

        $array_key = array_keys( $postsByYear);
        $lastYear = array_shift( $array_key );

       }
       elseif(!empty($postYear))
       {
         $lastYear = $postYear;
       }

       krsort($postsByYear);

       ?>
     <section class="ac-container">
    <?php foreach ($postsByYear as $year => $post): ?>
      <div class="<?= $type ?>-year-section">

        <h3 class="year"><?= $year ?></h3>
        <div class="yearEventsList">
      <?php foreach ( $post as $p): ?>
          <div id="post<?= $p['id']?>" class="events-list--event row clearfix">

            <?php if(!empty($p['thumbnail'])): ?>
              <div class="thumbnail col-4 column">
                <?= $p['thumbnail']; ?>
              </div>
            <?php endif; ?>

              <div class="event-info <?php echo (!empty($p['thumbnail'])? "col-8": "col-12"); ?> column">

                <div class="wrapper">

                  <h4><?= $p['title'] ?></h4>

                  <p class="place"><?= $p['place'] ?></p>
                  <p class="date">
                    <?php _e( 'From', 'Minimal-Artistic-Portfolio' ); ?>
                    <?php echo ' ' . $p['beginDate']; ?>
                    <?php _e( 'to', 'Minimal-Artistic-Portfolio' ); ?>
                    <?php echo  ' ' . $p['endDate']; ?>
                  </p>

                  <a class="button" href="<?= $p['link'] ?>"><?php _e('Read more', 'Minimal-Artistic-Portfolio'); ?></a>
                </div><!-- .wrapper -->

              </div><!-- .eventInfo -->
            </div>
          <?php endforeach; ?>
          </div><!-- .year(Posts/Events)List-->
        <?php endforeach; ?>
        </div><!-- .(posts/events)year-section -->
      </section><!-- .ac-container -->
      <?php $encodeMapData = json_encode($mapData); return $encodeMapData;
    }
  } else {
    return null;
  }
}

function minartport_get_media( $postID )
{
  $attachments = get_posts( array(
      'order_by'        => 'menu_order',
      'order'          => 'ASC',
      'post_type'      => 'attachment',
      'posts_per_page' => -1,
      'post_parent'    => $postID,
   ) );

   if ( $attachments ) {

    $count = count($attachments);

    if($count == 1)
    {
      $class = "post-attachment mime-" . sanitize_title( $attachments[0]->post_mime_type );
      $thumbimg = wp_get_attachment_image( $attachments[0]->ID, 'thumbnail-size' );
      echo '<div class="' . $class . ' minartport-single-image">' . $thumbimg . '</div>';
      return $count;
    }
    if($count > 1)
    {
      echo '<div class="minartport-gallery">';
      foreach ( $attachments as $attachment ) {
          $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
          $thumbimg = wp_get_attachment_image( $attachment->ID, 'full' );
          echo $thumbimg;
      }
      echo '</div><!-- .aap-gallery -->';
      return $count;
    }

   }

}
function  strip_shortcode_gallery( $content ) {
    preg_match_all( '/'. get_shortcode_regex() .'/s', $content, $matches, PREG_SET_ORDER );
    if ( ! empty( $matches ) ) {
        foreach ( $matches as $shortcode ) {
            if ( 'gallery' === $shortcode[2] ) {
                $pos = strpos( $content, $shortcode[0] );
                if ($pos !== false)
                    return substr_replace( $content, '', $pos, strlen($shortcode[0]) );
            }
        }
    }
    return $content;
}
function namespace_add_custom_types( $query ) {

  if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
        list_content_type();

        $query->set( 'post_type', $post_type);
    	  return $query;
	}
}
add_filter( 'pre_get_posts', 'namespace_add_custom_types' );

/*---------------IF NEW EVENT-SHOW-IT-ON-ON-HOME------------*/
function query_event_by_date()
{

  global $post;
  $today = date('Y-m-d');

  $allEvents = new WP_Query( array( 'post_type' => 'event', 'post_status' => 'publish', 'posts_per_page' => -1) );
  $actualEvents = array();
  $count = 0;
  if ( $allEvents->have_posts() )
  {
    while( $allEvents->have_posts()): $allEvents->the_post();

      $eventId = get_the_ID();
      $endDate = get_post_meta($eventId, 'ENDDATE', 'true');


      if($endDate > $today) :

        $actualEvents[$eventId]['eventId'] = $eventId;
        $actualEvents[$eventId]['place'] = get_post_meta( $eventId, 'PLACE', 'true');
        $actualEvents[$eventId]['latt'] = get_post_meta( $eventId, 'LATT', true);
        $actualEvents[$eventId]['long'] = get_post_meta( $eventId, 'LONG', true); ?>
      <?php if ($count == 0) : ?>

        <header class="entry-header actual-event">
          <h1 class="entry-title">&rdsh; <?php echo __('Coming soon / Now', 'Minimal-Artistic-Portfolio'); ?></h1>
        </header>

      <?php endif; ?>
        <?php
        $GLOBALS['latt']= $actualEvents[$eventId]['latt'];
        $GLOBALS['long']= $actualEvents[$eventId]['long'];
        ?>

        <div id="event-<?php echo $eventId; ?>" class="row event-on-home">
          <h3><?php echo get_the_title( $eventId ); ?></h3>

          <div class="col-6 column ">
            <?php the_post_thumbnail( 'full' ); ?>
          </div>

          <div class="col-6 column event-info">
            <h2><?php echo $actualEvents[$eventId]['place']; ?></h2>
            <p class="date">
              <?php _e( 'From', 'Minimal-Artistic-Portfolio' ); ?><?php echo ' ' . date_i18n('j F Y', strtotime(get_post_meta( get_the_ID(), 'BEGINDATE', 'true') ) ); ?>
              <?php _e( 'to', 'Minimal-Artistic-Portfolio' ); ?><?php echo ' ' . date_i18n('j F Y', strtotime( $endDate ) ); ?>
            </p>
            <a class="button" href="<?php echo get_permalink($eventId); ?>"><?php _e('Read more', 'Minimal-Artistic-Portfolio'); ?></a>

          </div><!-- /.col-6 -->
        </div><!-- /.row -->

        <?php $count++;
      endif;
    endwhile;
    return $actualEvents;
  }
  wp_reset_query();

}
/*------SHOW-CUSTOM-POST-TYPES-IN-DASHBOARD-AT-A-GLANCE-WIDGET-------------*/
add_action( 'dashboard_glance_items', 'cpad_at_glance_content_table_end' );

function cpad_at_glance_content_table_end() {
    $args = array(
        'public' => true,
        '_builtin' => false
    );
    $output = 'object';
    $operator = 'and';

    $post_types = get_post_types( $args, $output, $operator );
    foreach ( $post_types as $post_type ) {
        $num_posts = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish) );
        if ( current_user_can( 'edit_posts' ) ) {

            $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . __($text, 'Minimal-Artistic-Portfolio') . '</a>';

            echo '<li class="post-count ' . $post_type->name . '-count">'. $output . '</li>';


        }
    }
}
/*------SHOW-CUSTOM-POST-TYPES-IN-DASHBOARD-ACTIVITY-WIDGET------*/


// unregister the default activity widget
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
function remove_dashboard_widgets() {

    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);

}

// register your custom activity widget
add_action('wp_dashboard_setup', 'add_custom_dashboard_activity' );
function add_custom_dashboard_activity() {
    wp_add_dashboard_widget('custom_dashboard_activity', 'Activities', 'custom_wp_dashboard_site_activity');
}

// the new function based on wp_dashboard_recent_posts (in wp-admin/includes/dashboard.php)
function wp_dashboard_recent_post_types( $args ) {

/* Chenged from here */

	if ( ! $args['post_type'] ) {
		$args['post_type'] = 'any';
	}

	$query_args = array(
		'post_type'      => $args['post_type'],

/* to here */

		'post_status'    => $args['status'],
		'orderby'        => 'date',
		'order'          => $args['order'],
		'posts_per_page' => intval( $args['max'] ),
		'no_found_rows'  => true,
		'cache_results'  => false
	);
	$posts = new WP_Query( $query_args );

	if ( $posts->have_posts() ) {

		echo '<div id="' . $args['id'] . '" class="activity-block">';
    /*
		if ( $posts->post_count > $args['display'] ) {
			echo '<small class="show-more hide-if-no-js"><a href="#">' . sprintf( __( 'See %s more…'), $posts->post_count - intval( $args['display'] ) ) . '</a></small>';
		}
    */
		echo '<h4>' . $args['title'] . '</h4>';

		echo '<ul>';

		$i = 0;
		$today    = date( 'Y-m-d', current_time( 'timestamp' ) );
		$tomorrow = date( 'Y-m-d', strtotime( '+1 day', current_time( 'timestamp' ) ) );

		while ( $posts->have_posts() ) {
			$posts->the_post();

			$time = get_the_time( 'U' );
			if ( date( 'Y-m-d', $time ) == $today ) {
				$relative = __( 'Today' );
			} elseif ( date( 'Y-m-d', $time ) == $tomorrow ) {
				$relative = __( 'Tomorrow' );
			} else {
				/* translators: date and time format for recent posts on the dashboard, see http://php.net/date */
				$relative = date_i18n( __( 'M jS' ), $time );
			}

 			$text = sprintf(
				/* translators: 1: relative date, 2: time, 4: post title */
 				__( '<span>%1$s, %2$s</span> <a href="%3$s">%4$s</a>' ),
  				$relative,
  				get_the_time(),
  				get_edit_post_link(),
  				_draft_or_post_title()
  			);

 			$hidden = $i >= $args['display'] ? ' class="hidden"' : '';
 			echo "<li{$hidden}>$text</li>";
			$i++;
		}

		echo '</ul>';
		echo '</div>';

	} else {
		return false;
	}

	wp_reset_postdata();

	return true;
}

// The replacement widget
function custom_wp_dashboard_site_activity() {

    echo '<div id="activity-widget">';

    $future_posts = wp_dashboard_recent_post_types( array(
        'post_type'  => 'any',
        'display' => 3,
        'max'     => 7,
        'status'  => 'future',
        'order'   => 'ASC',
        'title'   => __( 'Publishing Soon' ),
        'id'      => 'future-posts',
    ) );

    $recent_posts = wp_dashboard_recent_post_types( array(
        'post_type'  => 'any',
        'display' => 3,
        'max'     => 7,
        'status'  => 'publish',
        'order'   => 'DESC',
        'title'   => __( 'Recently Published' ),
        'id'      => 'published-posts',
    ) );

    $recent_comments = wp_dashboard_recent_comments( 10 );

    if ( !$future_posts && !$recent_posts && !$recent_comments ) {
        echo '<div class="no-activity">';
        echo '<p class="smiley"></p>';
        echo '<p>' . __( 'No activity yet!' ) . '</p>';
        echo '</div>';
    }

    echo '</div>';
}
/*---------ADD-CPT-PICTO-TO-WIDGET-------------*/
function add_admin_styles() {

    echo '<link href="'. get_template_directory_uri() .'/prod/css/admin-css-hack.css"  rel="stylesheet">';
}

add_action('admin_head', 'add_admin_styles');

/*---------SHOW-POSTS-THUMBNAILS-IN-FEED-------------*/
function aap_post_thumbnail_feeds($content) {
  global $post;
  if(has_post_thumbnail($post->ID)) {
    $content = '<div>' . get_the_post_thumbnail($post->ID) . '</div>' . $content;
  }
  return $content;
}
add_filter('the_excerpt_rss', 'aap_post_thumbnail_feeds');
add_filter('the_content_feed', 'aap_post_thumbnail_feeds');
?>
