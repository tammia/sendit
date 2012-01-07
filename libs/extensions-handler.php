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





function sendit_morefields()
{
     /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     	custom fields loop and form input auto generation
     +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

	$sendit_morefields=get_option('sendit_dynamic_settings');
	$markup='';	
 	$arr=json_decode($sendit_morefields);
 	if(!empty($arr)): 	
	 	foreach($arr as $k=>$v):
	 		$markup.= '<p><label for="'.$v->name.'">'.$v->name.'</label>';
	 		$markup.= '<input type="text" name="'.$v->name.'" class="'.$v->class.' '.$v->rules.'"></p>'; 		
	 	endforeach;
 	endif;
	return $markup; 	
}





function sendit_morefields_admin()
{
     /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     	custom fields loop and form input auto generation
     +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

	$sendit_morefields=get_option('sendit_dynamic_settings');
	$markup='';	
 	$arr=json_decode($sendit_morefields);
 	$i=0;
 	
	if(!empty($arr)): 	
 		foreach($arr as $k=>$v):
 			$i++;
 			$markup.= '<li class="campo" id="campo'.$i.'">
				<label for="sendit_field[name]['.$i.']">'.__('Field name', 'sendit').'</label><input type="text" size="20" name="sendit_field['.$i.'][name]" id="sendit_field[name]['.$i.']" value="'.$v->name.'">	
				<label for="sendit_field[class]['.$i.']">'.__('Field css class', 'sendit').'</label><input type="text" size="20" name="sendit_field['.$i.'][class]" id="sendit_field[class]['.$i.']" value="'.$v->class.'">
				<label for="sendit_field[rules]['.$i.']">'.__('Field rules', 'sendit').'</label><select name="sendit_field['.$i.'][rules]" id="sendit_field['.$i.'][rules]"><option value="required">required</option><option value="required">required</option></select>
				<a class="remove_fields button-secondary">'.__('Remove','sendit').'</a></li>';
 		endforeach;
 	endif;
	return $markup; 	
}




function sendit_fields_counter()
{
     /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     	return the counter of fields
     +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

	$sendit_morefields=get_option('sendit_dynamic_settings');
	$arr=json_decode($sendit_morefields);	
	 	$i=0;
	if(!empty($arr)):
	 	foreach($arr as $k=>$v):
 			$i++;
 		endforeach;
	endif;
	return $i+1;
}

	


?>