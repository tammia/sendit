<?php 
/**/

function sendit_custom_post_type_init() 
{
	/***************************************************
	+++ custom post type: newsletter extract from Sendit Pro
	***************************************************/	
  $labels = array(
    'name' => _x('Newsletters', 'post type general name'),
    'singular_name' => _x('Newsletter', 'post type singular name'),
    'add_new' => _x('Add New', 'newsletter'),
    'add_new_item' => __('Add New newsletter'),
    'edit_item' => __('Edit newsletter'),
    'new_item' => __('New newsletter'),
    'view_item' => __('View newsletter'),
    'search_items' => __('Search newsletter'),
    'not_found' =>  __('No newsletters found'),
    'not_found_in_trash' => __('No newsletters found in Trash'), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => false,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor','thumbnail'),
	'rewrite' => array(
    'slug' => 'newsletter',
    'with_front' => FALSE

  ),
	'register_meta_box_cb' => 'sendit_add_custom_box'


  ); 
  register_post_type('newsletter',$args);

}

add_filter('post_updated_messages', 'newsletter_updated_messages');
function newsletter_updated_messages( $messages ) {

  $messages['newsletter'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Newsletter updated. <a href="%s">View newsletter</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Newsletter updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Newsletter restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Newsletter published. <a href="%s">View newsletter</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Newsletter saved.'),
    8 => sprintf( __('Newsletter submitted. <a target="_blank" href="%s">Preview newsletter</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Newsletter scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview newsletter</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Newsletter draft updated. <a target="_blank" href="%s">Preview newsletter</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

add_filter( 'gettext', 'sendit_change_publish_button', 10, 2 );

function sendit_change_publish_button( $translation, $text ) {
if ( 'newsletter' == get_post_type())
if ( $text == 'Publish' )
    return 'Save or Send Newsletter';

return $translation;
}




//display contextual help for Newsletters
add_action( 'contextual_help', 'add_help_text', 10, 3 );

function add_help_text($contextual_help, $screen_id, $screen) { 
$contextual_help =  var_dump($screen); // use this to help determine $screen->id
  if ('newsletter' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Very important notices for a better use:','sendit') . '</p>' .
      '<ul>' .
      '<li>' . __('Insert your favorite content to send using the editor exactly in the same way you edit post, remember this content will be sent so be careful.','sendit') . '</li>' .
      '<li>' . __('Specify the mailing list from the radio men&ugrave; at the bottom of edit','sendit') . '</li>' .
      '</ul>' .
      '<p>' . __('If you want to schedule immediatly the newsletter check YES:','sendit') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.','sendit') . '</li>' .
      '<li>' . __('Newsletter will be scheduled to be sent with your favorite settings.','sendit') . '</li>' .
      '</ul>' .
      '<p><strong>' . __('For more information:') . '</strong></p>' .
      '<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>','sendit') . '</p>' .
      '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>','sendit') . '</p>' ;
  } elseif ( 'edit-newsletter' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the table of Newsletter system.','sendit') . '</p>' ;
  }
  return $contextual_help;
}


function extract_posts()
{
	$posts=get_posts();
	return $posts;
}

function sendit_add_custom_box() 
{
  if( function_exists( 'add_meta_box' ))
  {
	add_meta_box( 'content_choice', __( 'Append content from existing posts', 'sendit' ), 
		          'sendit_content_box', 'newsletter', 'advanced','high' );
    add_meta_box( 'mailinglist_choice', __( 'Save and Send', 'sendit' ), 
                'sendit_custom_box', 'newsletter', 'advanced' );
   } 
}


function sendit_custom_box($post) {
	$sendit = new Actions();
	global $wpdb;
	$choosed_list = get_post_meta($post->ID, 'sendit_list', TRUE);

	$table_email =  SENDIT_EMAIL_TABLE;   
	$table_liste =  SENDIT_LIST_TABLE;   
    $liste = $wpdb->get_results("SELECT id_lista, nomelista FROM $table_liste ");
	echo '<label for="send_now">'.__('Action', 'sendit').': </label>';
	echo '<select name="send_now" id="send_now"><option value="0">'.__( 'Save and send later', 'sendit' ).'</option><option value="1">'.__( 'Send now', 'sendit' ).'</option></select><br />';
	echo '<h4>'.__('Select List', 'sendit').'</h4>';
	foreach($liste as $lista): 
		$subscribers=count($sendit->GetSubscribers($lista->id_lista));?>
    	<input type="radio" name="sendit_list" value="<?php echo $lista->id_lista; ?>" <?php if ($choosed_list == $lista->id_lista) echo "checked=1";?>> <?php echo $lista->nomelista; ?>  subscribers: <?php echo $subscribers; ?><br/>
	<?php endforeach; ?>


	<input type="hidden" name="sendit_noncename" id="sendit_noncename" value="<?php echo wp_create_nonce( 'sendit_noncename'.$post->ID );?>" />

	<?php
}

function sendit_content_box($post) {
	global $post;
	$posts=extract_posts();
	foreach($posts as $post): ?>
	<div class="post_box">
	<table>
		<tr>
			<th style="width:200px; text-align:left;"><?php echo $post->post_title; ?></th><td><a class="button-secondary send_to_editor">Send to Editor &raquo;</a></td>
		</tr>
	</table>
    	<div class="content_to_send" style="display:none;"><h2><a href="<?php echo get_permalink( $post->ID); ?>"><?php echo $post->post_title; ?></a></h2><?php echo apply_filters('the_excerpt',$post->post_content); ?><a href="<?php echo get_permalink($post->ID); ?>">Read more...</a>
    	</div>
    </div>

	<?php endforeach; ?>


	<input type="hidden" name="sendit_noncename" id="sendit_noncename" value="<?php echo wp_create_nonce( 'sendit_noncename'.$post->ID );?>" />

	<?php
}

function sendit_save_postdata( $post_id )
{
 
	if ( !wp_verify_nonce( $_POST['sendit_noncename'], 'sendit_noncename'.$post_id ))
		return $post_id;
 
 	 if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	    return $post_id;
 
  	if ( !current_user_can( 'edit_page', $post_id ) )
	    return $post_id;
 
	$post = get_post($post_id);
	if ($post->post_type == 'newsletter') {
		update_post_meta($post_id, 'send_now', $_POST['send_now']);	
		update_post_meta($post_id, 'sendit_list', $_POST['sendit_list']);
		//update_post_meta($post_id, 'subscribers', get_list_subcribers($_POST['sendit_list']));
		//update_post_meta($post_id, 'sendit_scheduled',$_POST['sendit_scheduled']);
		return(esc_attr($_POST));
	}
}



function send_newsletter($post_ID)
{
	$sendit = new Actions();
	$article = get_post($post_ID);
	$send_now = get_post_meta($post_ID, 'send_now',true);
	$sendit_list = get_post_meta($post_ID, 'sendit_list',true);	
	$table_liste =  SENDIT_LIST_TABLE;
	$list_detail = $sendit->GetListDetail($sendit_list);
	$subscribers = $sendit->GetSubscribers($sendit_list); //only confirmed
	/*+++++++++++++++++++ TEMPLATE EMAIL +++++++++++++++++++++++++++++++++++++++++*/
	$header=$list_detail->header;
	$footer=$list_detail->footer;
	$email_from=$list_detail->email_lista;
	
	/*+++++++++++++++++++ HEADERS EMAIL +++++++++++++++++++++++++++++++++++++++++*/
	$email=$email_from;
	$headers= "MIME-Version: 1.0\n" .
	"From: ".$email." <".$email.">\n" .
	"Content-Type: text/html; charset=\"" .
	get_option('blog_charset') . "\"\n";
	/*+++++++++++++++++++ CONTENT EMAIL +++++++++++++++++++++++++++++++++++++++++*/
	$title = $article->post_title;
	$content = apply_filters('the_content',$article->post_content);
	$newsletter_content=$header.$content.$footer;
	$readonline = get_permalink($post_ID);

	if($send_now==1):
		foreach($subscribers as $subscriber):
			wp_mail($subscriber->email, $title ,$newsletter_content, $headers, $attachments);		
		endforeach;
	endif;
}



function export_subscribers_screen()
{ ?>
	<div class="wrap">

	<h2><?php echo __('To export Sendit mailing list you need to buy Sendit pro exporter','sendit');?></h2>
		<p><?php echo __('With Sendit pro export tool (available now for only 5 euros) you will be able to export and reimport as CSV files all your Sendit subscribers'); ?></p>
		<a class="button primary" href="http://sendit.wordpressplanet.org/plugin-shop/wordpress-plugin/sendit-pro-csv-list-exporter/"><?php echo __('Buy this plugin Now for 5 euros', 'Sendit'); ?></a>
	
	</div>
<? }

function sendit_morefields_screen()
{ ?>
	<div class="wrap">

	<h2><?php echo __('To add and manage more fields to your subscription form you need to buy Sendit More Fields');?></h2>
		<p><?php echo __('With Sendit More Fields tool (available now for only 5 euros) you will be able to create manage and add additional fields and store as serialized data to your subscriptions. Also you can use to personalize your newsletter with something like dear {Name}'); ?></p>
		<a class="button primary" href="http://sendit.wordpressplanet.org/plugin-shop/wordpress-plugin/sendit-more-fields/"><?php echo __('Buy this plugin Now for 5 euros', 'Sendit'); ?></a>
	
	</div>
<? }

add_filter("manage_edit-newsletter_columns", "newsletter_columns");

function newsletter_columns($columns)
{

	global $post;
	$columns = array(
		"cb" => "<input type=\"checkbox\" name=\"post[]\" value=\"".$post->ID."\" />",
		"title" => "Newsletter Title",
		"description" => "Description",
		"queued" => "queued",
		"subscribers" => "subscribers",
		"startnum" => "sent",
		"opened" => "opened",
		"next_send" => "Next Send",
		"list" => "Receiver list"				
	);
	return $columns;
}


// Add to admin_init function
add_action('manage_posts_custom_column', 'manage_newsletter_columns', 10, 2);

function manage_newsletter_columns($column_name, $id) {
	global $wpdb;
	switch ($column_name) {
	case 'id':
		echo $id;
	    break;

	case 'images':
		// Get number of images in gallery
		$num_images = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_parent = {$id};"));
		echo $num_images; 
		break;
		
	case 'list':
		echo get_post_meta($id,'sendit_list',TRUE);
		//get_queued_newsletter();
	break;
	
	case 'subscribers':
		echo get_post_meta($id,'subscribers',TRUE);
	break;

	case 'startnum':
		echo get_post_meta($id,'startnum',TRUE);
	break;

	case 'opened':
	/*
		$viewed = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".TRACKING_TABLE." WHERE newsletter_ID = {$id};"));
		$unique_visitors = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT(reader_ID) FROM ".TRACKING_TABLE." WHERE newsletter_ID = {$id};"));
		
		echo 'viewed:'.$viewed. ' times by: '.count($unique_visitors).' unique readers';
	break;
	*/
	
	case 'next_send':
	echo strftime("%d/%m/%Y/ - %H:%M ",wp_next_scheduled('sendit_five_event'));
	//print_r(get_option('sendit_cron_ten_minutes'));
	break;


	
	default:
	break;
	} // end switch
}
	


?>