<?php
function sendit_shortcode($atts) {
     $markup=sendit_markup($atts[id]);    
     return $markup;
}

add_shortcode('newsletter', 'sendit_shortcode');



function sendit_markup($id)
{
     /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     	The standard HTML form for all usage (widget shortcode etc)
     +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

	$sendit_markup=get_option('sendit_markup');	
 	$sendit_markup=str_replace("{list_id}",$id, $sendit_markup);
 	
 	if(function_exists('sendit_morefields')):
 		$sendit_markup=str_replace("{sendit_morefields}",sendit_morefields(), $sendit_markup);
	else:
 		$sendit_markup=str_replace("{sendit_morefields}",'', $sendit_markup);
	endif;
			
 	$sendit_markup=str_replace("{subscribe_text}", get_option('sendit_subscribe_button_text'), $sendit_markup);
 	if(is_user_logged_in()):
		$sendit_markup.='<small><a href="wp-admin/admin.php?page=sendit_widget_settings">'.__('Customize Widget','sendit').'</a></small>';
 	endif;
 	return $sendit_markup;

}

function sendit_js() 
{
  // Spingo js su header (x luca)
  wp_print_scripts( array('jquery' ));

  // Define custom JavaScript function
?>
		<script type="text/javascript">
		jQuery(document).ready(function(){	
		jQuery('input#email_add').focus(function() {
   			jQuery(this).val('');
		});
		
		
		jQuery('input.req').blur(function() {

   			if (jQuery(this).val() == "") {
  				jQuery(this).after('<span class="sendit_error">Required!</span>');
 			 	valid = false;
			} else {
				  jQuery(this).find('span.sendit_error').hide();
				  valid = true;				
			}

		});


			jQuery('#sendit_subscribe_button').click(function(){

				jQuery.ajax({
				beforeSend: function() { jQuery('#sendit_wait').show(); jQuery('#sendit_subscribe_button').hide();},
		        complete: function() { jQuery('#sendit_wait').hide(); jQuery('#sendit_subscribe_button').show(); },
				type: "POST",
		      	//data: ({jQuery("#senditform").serialize()}),
		      	data: ({options : jQuery("#senditform").serialize(), email_add : jQuery('#email_add').val(),lista : jQuery('#lista').val()}),
		      	url: '<?php bloginfo( 'wpurl' ); ?>/wp-content/plugins/sendit/submit.php',
		  		success: function(data) {
		    	<?php if(get_option('sendit_response_mode')=='alert'): ?>
		   		alert(data);
		   		<?php else: ?>
		    	jQuery('#dati').html(data);
		    	<?php endif; ?>

		   		
		  }
		});
			});
		});
		

	
function checkemail(e){
  var emailfilter = /^w+[+.w-]*@([w-]+.)*w+[w-]*.([a-z]{2,4}|d+)$/i
  return emailfilter.test(e);
}
function checkphone(e) {
 var filter = /[0-9]/
 return filter.test(e);
}
		
		</script>
		

		
		<?php
} 


function DisplayForm()
{
    if ( !function_exists('register_sidebar_widget') ){return; }
    register_sidebar_widget('Sendit Widget','JqueryForm');
    register_widget_control('Sendit Widget','Sendit_widget_options', 200, 200);    
}

function JqueryForm($args) {
    global $dcl_global;
    extract($args);
    $lista= get_option('id_lista');
    //before_widget,before_title,after_title,after_widget

    $form_aggiunta=$before_widget."
             ".$before_title.get_option('titolo').$after_title;
  			$form_aggiunta.=sendit_markup($lista);
           // if (!$dcl_global) $form_aggiunta.="<p><small>Sendit <a href=\"http://www.giuseppesurace.com\">Wordpress  newsletter</a></small></p>";
            $form_aggiunta.=$after_widget;
    
    echo $form_aggiunta;
}

function Sendit_widget_options() {
        if ($_POST['id_lista']) {
            $id_lista=$_POST['id_lista'];
            $titolo=$_POST['titolo'];
            update_option('id_lista',$id_lista);
            update_option('titolo',$_POST['titolo']);
        }
        $id_lista = get_option('id_lista');
        $titolo = get_option('titolo');
        //titolo
        echo '<p><label for="titolo">'.__('Newsletter title: ', 'sendit').' <input id="titolo" name="titolo"  type="text" value="'.$titolo.'" /></label></p>';
        //id della mailing list
        echo '<p><label for="id_lista">'.__('Mailing list ID: ', 'sendit').' <input id="id_lista" name="id_lista" type="text" value="'.$id_lista.'" /></label></p>';
        
            
    }


function sendit_loading_image() {
    $siteurl = get_option('siteurl');
    $img_url = $siteurl . '/wp-content/plugins/sendit/images/loading.gif';
    echo '<style type="text/css">#sendit_wait{background:url('.$img_url.') no-repeat; height:40px;margin:10px;display:block;}</style>';    
}

function sendit_register_head() {
    //$siteurl = get_option('siteurl');
    //$url = $siteurl . '/wp-content/plugins/sendit/sendit.css';
    //echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
    echo '<style type="text/css">'.get_option('sendit_css').'</style>';
}

function sendit_admin_js()
{
	?>

    <script type="text/javascript">
	jQuery(document).ready(function($) {        
     
               $(".send_to_editor").click( function() {                   
                   var post_title = $(this).closest("div.post_box").find(".content_to_send").html();
                   alert('Article added to your newsletter');
                   send_to_editor(post_title);
              });
 });
	</script>
<?php
 }


?>