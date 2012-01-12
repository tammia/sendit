<?php


function sendit_admin_head() {
  	wp_print_scripts( array('jquery-ui-draggable','jquery-ui-sortable' ));

    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/sendit/sendit-admin.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
	echo '<style type="text/css">'.get_option('sendit_css').'</style>';
	?>
	<script type="text/javascript">
function addFormField() {
	var id = document.getElementById("id").value;
	var remove_div ='#campo'+id; 
	
	jQuery("#divTxt").append('<div class="campo" id="campo'+id+'"><p id="row'+id+'" class="leg"><label for="sendit_field['+id+'][name]"><?php echo __('Field name', 'sendit'); ?></label><input type="text" size="20" name="sendit_field['+id+'][name]" id="sendit_field['+id+'][name]"><label for="sendit_field['+id+'][class]"><?php echo __('Field css class', 'sendit'); ?></label><input type="text" size="20" name="sendit_field['+id+'][class]" id="sendit_field['+id+'][class]"><label for="sendit_field['+id+'][rules]"><?php echo __('Field rules', 'sendit'); ?></label><select name="sendit_field['+id+'][rules]" id="sendit_field['+id+'][rules]"><option value="required">required</option><option value="not_required">-------</option></select><a class="button-secondary remove_fields" href="#">Remove</a></p></div>');
	
	
	id = (id - 1) + 2;
	document.getElementById("id").value = id;
}


jQuery('.remove_fields').live('click', function(){
jQuery(this).closest('.campo').remove();
});

	jQuery(function() {
		jQuery( "#sortable" ).sortable();
		jQuery( "#sortable" ).disableSelection();
	});
</script>
<?php } 

function ManageLists() {
    global $_POST;
    global $wpdb;
    
    //nome tabella LISTE
    $table_liste = $wpdb->prefix . "nl_liste";
    
    if($_POST['newsletteremail']!="" AND $_POST['com']!="EDIT"):           
        $liste_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_liste where email_lista ='$_POST[newsletteremail]';");
           $wpdb->query("INSERT INTO $table_liste (email_lista, nomelista) VALUES ('$_POST[newsletteremail]', '$_POST[newslettername]')");
           echo '<div id="message" class="updated fade"><p><strong>'.__('Mailing list created succesfully!', 'sendit').'</strong></p></div>';   
    endif;
    
    if($_POST['com']=="EDIT") :    
    	$header = $_POST['header'];
    	$footer = $_POST['footer'];        
        $aggiorno= $wpdb->query("UPDATE $table_liste set email_lista = '$_POST[newsletteremail]', nomelista = '$_POST[newslettername]', header='$header', footer='$footer' where id_lista = '$_POST[id_lista]'");
        $msg =  '<div id="message" class="updated fade"><p><strong>'.__('Mailing list updated', 'sendit').'</strong></p></div>';
    elseif($_POST['com']=="ADD") :       
        $newemail = __('email@here', 'sendit');
        $newname = __('New mailing list', 'sendit');            
        $ins= $wpdb->query("insert into $table_liste (email_lista, nomelista) values('$newemail', '$newname')");        
        $msg = '<div id="message" class="updated fade"><p><strong>'.__('Mailing created successfully!', 'sendit').'</strong></p></div>';        
    elseif($_POST['com']=="DEL") :                    
        $ins= $wpdb->query("delete from $table_liste where id_lista = $_POST[id_lista]");        
        $msg = '<div id="message" class="updated fade"><p><strong>'.__('Mailing deleted successfully!', 'sendit').'</strong></p></div>';    
    endif;

    if(($_GET['update']==1)&&(!isset($_POST['com']))) :
      $listacorrente= $wpdb->get_row("select * from $table_liste where id_lista = '$_GET[id_lista]'");
      $com="EDIT";
    endif;

    if($_GET['delete']==1) :
       //div che avvisa prima della cancellazione con form
       $msg = "<div class=\"error\"class=\"wrap\"><p>".sprintf(__('Are You sure to delete %d list? it will delete all mailing list and subscribers data ', 'sendit'), $_GET['id_lista'])." ".$listacorrente->nomelista."</p>
                <form action=\"admin.php?page=lists-management\" method=\"post\" name=\"delml\">
                    <input type=\"hidden\" name=\"id_lista\" value = \"".$_GET['id_lista']."\">
                    <input type=\"submit\" name=\"com\" value = \"DEL\">
                </form>
                </div>";
    endif;

            echo "<div class=\"wrap\"class=\"wrap\"><h2>".__('Lists Management', 'sendit')." ".$listacorrente->nomelista."</h2>";
              
            
            
                    
            //esco il messaggio
                    echo $msg;        
            
                        
            
                    
                    $table_liste = $wpdb->prefix . "nl_liste";
                    $liste= $wpdb->get_results("select * from $table_liste");
                    
                    
                    echo "
                    <form action='$_SERVER[REQUEST_URI]' method='post' name='addml'><table class=\"wp-list-table widefat fixed posts\">
                    <input type='submit' class='button-primary sendit_actionbuttons' name='go' value='".__('Create new list', 'sendit')."'>
                   
                            <thead>
                            <tr>
                            <th colspan=\"2\">".__('Available lists', 'sendit')."
                            </th>
                            <th align=\"right\">
                            <label for='com'>
                            <input type='hidden' name='com' value='ADD'>
                            </label>
                            
                        </form></th>
                            </tr>
                            </thead>
                            <tbody>
                            ";
                    foreach ($liste as $lista) {
                        
                        echo "<tr>
                            <td><p>".__('Mailing list', 'sendit')." ". $lista->id_lista." - ". $lista->email_lista. " - "  .$lista->nomelista."</p></td>
                            <td><p><a class=\"button-secondary\" href=\"admin.php?page=lists-management&update=1&id_lista=".$lista->id_lista."\">".__('Edit', 'sendit')."</a></p></td>
                        	<td><p><a href=\"admin.php?page=lists-management&delete=1&id_lista=".$lista->id_lista."\">".__('Delete', 'sendit')."</a></td></p></tr>";
                        
                        }
                        
                        echo "</tbody></table>";
                        
            if($_GET['id_lista'] and !$_GET['delete']) :
                        
            echo "<form action='$_SERVER[REQUEST_URI]' method='post' >
            <p>".__('Newsletter options', 'sendit')."</p>
            <table>
                    
                    <tr>
                        <th scope=\"row\" width=\"200\"><label for=\"newsletteremail\">".__('from email', 'sendit')."</label><th>
                        <td><input type=\"text\" name=\"newsletteremail\" value=\"".$listacorrente->email_lista."\" ></td>
                    </tr>                    
                    <tr>
                    <th scope=\"row\" ><label for=\"newslettername\">".__('Newsletter name', 'sendit')."</label><th>
                    <td><input type=\"text\" name=\"newslettername\"  value=\"".$listacorrente->nomelista."\"><input type=\"hidden\" name=\"com\" value=\"".$com."\">
                    <input type=\"hidden\" name=\"id_lista\" value=\"".$_GET[id_lista]."\">
                    </td></tr>
                    <tr>
                        <th colspan=\"2\"><h2>".__('Template', 'sendit')."</h2>
                        <p>".__('Header and Footer (XHTML code)', 'sendit')."</p>
                        </th>
                    </tr>
                    <tr><th scope=\"row\" ><label for=\"header\">".__('Header', 'sendit')."</label><th>
                    <td><textarea name=\"header\" rows=\"5\" cols=\"50\">".$listacorrente->header."</textarea></td></tr>                    
                        <tr><th scope=\"row\" ><label for=\"footer\">".__('Footer', 'sendit')."</label><th>
                    <td><textarea name=\"footer\" rows=\"5\" cols=\"50\">".$listacorrente->footer."</textarea></td></tr>                   
                    <tr><th scope=\"row\" ><th>
                    <td><p class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"salva\" value=\"".__('Save', 'sendit')."\"></p></td>
                    </tr>                    
                 </table>
                    </form>";
                endif;        
                echo "</div>";
    
}

function SmtpSettings()
{
    
    /*
    $mail->Host = get_option('sendit_smtp_host'); // Host
    $mail->Hostname = get_option('sendit_smtp_hostname');// SMTP server hostname
    $mail->Port  = get_option('sendit_smtp_port');// set the SMTP port
    */

    $markup= "<div class=\"wrap\"class=\"wrap\"><h2>".__('Sendit SMTP settings', 'sendit');
    
    if($_POST):
        update_option('sendit_smtp_host',$_POST['sendit_smtp_host']);
        update_option('sendit_smtp_hostname',$_POST['sendit_smtp_hostname']);
        update_option('sendit_smtp_port',$_POST['sendit_smtp_port']);
		
        update_option('sendit_smtp_authentication',$_POST['sendit_smtp_authentication']);
        update_option('sendit_smtp_username',$_POST['sendit_smtp_username']);
        update_option('sendit_smtp_password',$_POST['sendit_smtp_password']);
        update_option('sendit_smtp_ssl',$_POST['sendit_smtp_ssl']);
        
        //new from 1.5.0!!!
        update_option('sendit_sleep_time',$_POST['sendit_sleep_time']);
        update_option('sendit_sleep_each',$_POST['sendit_sleep_each']);
        

        
        $markup.='<div id="message" class="updated fade"><p><strong>'.__('Settings saved!', 'sendit').'</strong></p></div>';
    endif;
    $markup.='<h3>'.__('Smtp settings are required only if you want to send mail using an SMTP server','sendit').'</h3>
    <p>'.__('By default Sendit will send newsletter using the mail() function, if you sant to send mail using SMTP server you just have to type your settings here').'</p>
<form method="post" action="'.$_SERVER[REQUEST_URI].'">
<table class="form-table">
    <tr>
        <th><label for="sendit_smtp_host">SMTP host</label></th>
        <td><input name="sendit_smtp_host" id="sendit_smtp_host" type="text" value="'.get_option('sendit_smtp_host').'" class="regular-text code" /></td>
    </tr>

    <tr>
        <th><label for="sendit_smtp_port">SMTP port</label></th>
        <td><input name="sendit_smtp_port" id="sendit_smtp_hostname" type="text" value="'.get_option('sendit_smtp_port').'" class="regular-text code" /></td>
    </tr>
    <tr>
        <th colspan="2">
        <h3>'.__('Settings below are required only if SMTP server require authentication','sendit').'</h3>
        </th>
    </tr>    
    <tr>
        <th><label for="sendit_smtp_username">SMTP username</label></th>
        <td><input name="sendit_smtp_username" id="sendit_smtp_username" type="text" value="'.get_option('sendit_smtp_username').'" class="regular-text code" /></td>
    </tr>
    <tr>
        <th><label for="sendit_smtp_password">SMTP password</label></th>
        <td><input name="sendit_smtp_password" id="sendit_smtp_password" type="password" value="'.get_option('sendit_smtp_password').'" class="regular-text code" /></td>
    </tr>
    <tr>
        <th><label for="sendit_smtp_ssl">SMTP SSL</label></th>
        <td>
        	<select name="sendit_smtp_ssl" id="sendit_smtp_ssl">
        		<option value="'.get_option('sendit_smtp_ssl').'" selected="selected" />'.get_option('sendit_smtp_ssl').'</option>
        		<option value="">no</option>
        		<option value="ssl">SSL</option>
        		<option value="tls">TLS</option>
		</select>
        </td>
    </tr>


</table>
<div class="suggest">
<p>
<i>'.
__('Are you on panic for large mailing lists, bad delivery (spam etc)?','sendit').'<br />';

$markup.='<strong>Relax!</strong>'.__('Let SendGrid handle your email delivery used with Sendit. Get 25% off any plan by clicking my link.','sendit');

$markup.='<br /><a href="http://sendgrid.tellapal.com/a/clk/3Rv3Ng">http://sendgrid.tellapal.com/a/clk/3Rv3Ng</a><br />';

$markup.='SendGrid helps you reach more users instead of spam folders. Click this link to get your 25% discount on your first month\'s membership. Believe me you will be addicted!<br />';

$markup.='<a href="http://sendgrid.tellapal.com/a/clk/3Rv3Ng">http://sendgrid.tellapal.com/a/clk/3Rv3Ng</a>';

$markup.='<br />Best<br />Giuseppe</i>
</p></div>

<p class="submit">
    <input type="submit" name="submit" class="button-primary sendit_actionbuttons" value="'.__('Save settings', 'sendit').'" />
</p>
  </form>';

    $markup.='</div>';

    echo $markup;

}


function SenditWidgetSettings($c='')
{
    
	
    $markup= '<div class="wrap"class="wrap">';
    
    $markup.='<h2>'.__('Sendit Widget CSS/html settings', 'sendit').'</h2>';
   
    $c=md5(uniqid(rand(), true));
    if($_POST):
        update_option('sendit_subscribe_button_text',stripslashes($_POST['sendit_subscribe_button_text']));        
        update_option('sendit_response_mode',stripslashes($_POST['sendit_response_mode']));        
        update_option('sendit_markup',stripslashes($_POST['sendit_markup']));        
        update_option('sendit_css',stripslashes($_POST['sendit_css']));        
        $markup.='<div id="message" class="updated fade"><p><strong>'.__('Settings saved!', 'sendit').'</strong></p></div>';
        //$markup.='<div id="sendit_preview">'.sendit_markup(1).'</div>';
    endif;

    $markup.='<h3>'.__('Welcome to the new Sendit Newsletter plugin Panel').'</h3>';
    $markup.='<img style="float:left;margin:0 5px 0 0;"src="http://sendit.wordpressplanet.org/wp-content/uploads/sendit_big1.jpg" width="200" /><i>Welcome to the new Sendit plugin. Probably you were expeting the old form to send newsletter from here. As well i rebuilt and did a big refactoring of all plugin so its new. The new Sendit support custom post types so newsletters will be saved. The new plugin also containes a lot of functions requested directly by users so should be excited to test. You can finally built newsletter selecting content from posts (more than 1 post) just choosing from the <strong>custom meta box</strong></i><br />
  

<form method="post" action="'.$_SERVER[REQUEST_URI].'&c='.$c.'">
<table class="form-table">
    <tr>
        <th><label for="sendit_subscribe_button_text">Subscribtion button text</label></th>
        <td><input type="text" name="sendit_subscribe_button_text" id="sendit_subscribe_button_text" value="'.get_option('sendit_subscribe_button_text').'" /></td>
    </tr>
        <tr>
        <th><label for="sendit_response_mode">'.__('Response mode', 'sendit').'</label></th>
        <td>
        	<select name="sendit_response_mode">
        		<option value="'.get_option('sendit_response_mode').'" selected="selected">'.get_option('sendit_response_mode').'</option>
        		<option value="alert">Alert</option>
        		<option value="ajax">Ajax</option>
        	</select>
</td>
    </tr>
    <tr>
        <th><label for="sendit_markup">'.__('Select format for your posts to send', 'sendit').'</label></th>
        <td><textarea class="sendit_code source" rows="15" cols="70" name="sendit_markup" id="sendit_markup">'.get_option('sendit_markup').'</textarea></td>
    </tr>
    <tr>
        <th><label for="sendit_css">Subscription widget CSS markup</label></th>
        <td><textarea class="sendit_blackcss" rows="15" cols="70" name="sendit_css" id="sendit_css">'.get_option('sendit_css').'</textarea></td>
    </tr>

</table>


<p class="submit">
    <input type="submit" name="submit" class="button-primary sendit_actionbuttons" value="'.__('Save settings', 'sendit').'" />
</p>
  </form>';

    $markup.='</div>';

    echo $markup;

}


function MainSettings($c='')
{
    
	
    $markup= '<div class="wrap"class="wrap"><h2>'.__('Sendit', 'sendit').'</h2>';
    //$markup.='<label>Preview Area</label><div class="preview"></div>';
    $c=md5(uniqid(rand(), true));
    if($_POST):
        update_option('sendit_subscribe_button_text',stripslashes($_POST['sendit_subscribe_button_text']));        
        update_option('sendit_markup',stripslashes($_POST['sendit_markup']));        
        update_option('sendit_css',stripslashes($_POST['sendit_css']));        
    endif;
	$markup.='<div class="sendit_box_list sendit_box_menu"><h2>'.__('Lists and Template', 'sendit').'</h2>
			  	<a href="'.admin_url( 'admin.php?page=lists-management').'" class="button-primary">'.__('Create and manage lists', 'sendit').'</a>
			  </div>
			  <div class="sendit_box_design sendit_box_menu"><h2>'.__('Design Widget', 'sendit').'</h2>
			  	<a href="'.admin_url( 'admin.php?page=sendit_widget_settings').'" class="button-primary">'.__('Customize widget', 'sendit').'</a>
			  </div>
			  <div class="sendit_box_sendnewsletter sendit_box_menu"><h2>'.__('Send Newsletter', 'sendit').'</h2>
			  	<a href="'.admin_url( 'post-new.php?post_type=newsletter').'" class="button-primary">'.__('Create and send newsletter', 'sendit').'</a>
			  </div>';

	$markup.='<!-- start payment extensions --><div class="sendit_box_fields sendit_box_menu"><h2>'.__('Add more fields', 'sendit').'</h2>
			  	<a href="'.admin_url( 'admin.php?page=sendit_morefields_settings').'" class="button-primary">'.__('Add more fields', 'sendit').'</a>
			  </div>';
			  
	$markup.='<div class="sendit_box_export sendit_box_menu"><h2>'.__('Export mailing lists', 'sendit').'</h2>
			  	<a href="'.admin_url('admin.php?page=export-subscribers').'" class="button-primary">'.__('Save your list as CSV', 'sendit').'</a>
			  </div>
			  <div class="sendit_box_cron sendit_box_menu"><h2>'.__('Cron Settings', 'sendit').'</h2>
			  	<a href="'.admin_url('admin.php?page=cron-settings').'" class="button-primary">'.__('Cron settings', 'sendit').'</a>
			  </div>';	


    $markup.='</div>';

    echo $markup;

}


/**********PAGINA LISTA ISCRITTI**********/
function Iscritti() {
	require('pagination.class.php');
    global $_POST;
    global $wpdb;
    
    $table_email = $wpdb->prefix . "nl_email";
    
    //cancellazione provamoce
    if($_POST['delete'] && $_POST['id_email']):   

          $delete=$wpdb->query("delete from $table_email where id_email = '$_POST[id_email]'");         
           
           echo '<div id="message" class="updated fade"><p><strong>'.__("Email deleted succesfully!", "sendit").'</strong></p></div>';   
           //print_r($_POST);
   
    endif;
    
    //modifica provamoce
    if($_POST['update']):   
        //$code = md5(uniqid(rand(), true));
           
           $update=$wpdb->query("update $table_email set email = '$_POST[email]', magic_string='$_POST[code]', accepted = '$_POST[status]' where id_email = '$_POST[id_email]'");
           
           echo '<div id="message" class="updated fade"><p><strong>'.sprintf(__('email %s edited succesfully', 'sendit'), $_POST[email]).'</p></div>';   
           //print_r($_POST);
   
    endif;
    
    
   
   //aggiunta indirizzo o indirizzi email dalla textarea
  if($_POST['emails_add']!=""):   
 
  //ver 1.1 multiaddress support
  $email_add= explode("\n", $_POST['emails_add']);
 

  foreach ($email_add as $key => $value) {
      
      //echo $value."<br />";
        
	//validation fix 1.5.6 (also there!) {2,4}    
      if (!ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", trim($value))) :
       
               echo '<div id="message" class="error"><p><strong>indirizzo email '.$value.' non valido!</strong></p></div>';

      else :

        
            
            $user_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_email where email ='$value' and id_lista = '$_GET[lista]' order by email;");
            
                if($user_count>0) :
                    echo "<div class=\"error\"><p><strong>".sprintf(__('email %s already present', 'sendit'), $value)."</strong></p></div>";
                else :
                //genero stringa univoca x conferme e cancellazioni sicure
                    $code = md5(uniqid(rand(), true));
                    $wpdb->query("INSERT INTO $table_email (email,id_lista, magic_string, accepted) VALUES ('$value', '$_POST[id_lista]', '$code', 'y')");
                     echo '<div class="updated fade"><p><strong>'.sprintf(__('email %s added succesfully!', 'sendit'), $value).'</strong></p></div>';   
                 endif;    
        endif;
        
        
        
  }
  //fine ciclo for
        
        
        
     endif;

        
    $email_items = $wpdb->get_var("SELECT count(*) FROM $table_email where id_lista= '$_GET[lista]'"); // number of total rows in the database
	if($email_items > 0) {
		$p = new pagination;
		$p->items($email_items);
		$p->limit(20); // Limit entries per page
		$p->target("admin.php?page=lista-iscritti&lista=".$_GET['lista']);
		$p->currentPage($_GET[$p->paging]); // Gets and validates the current page
		$p->calculate(); // Calculates what to show
		$p->parameterName('paging');
		$p->adjacents(1); //No. of page away from the current page

		if(!isset($_GET['paging'])) {
			$p->page = 1;
		} else {
			$p->page = $_GET['paging'];
		}

		//Query for limit paging
		$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;

	} else {
		//echo "No Record Found";
	}

   
    
    $emails = $wpdb->get_results("SELECT id_email, id_lista, email, subscriber_info, magic_string, accepted FROM $table_email where id_lista= '$_GET[lista]' order by email $limit");
    //email confermat
    $emails_confirmed = $wpdb->get_results("SELECT id_email, id_lista, email, subscriber_info, magic_string, accepted FROM $table_email where id_lista= '$_GET[lista]' and accepted='y'");

    echo "<div class=\"wrap\"><h2>".__('Subscribers', 'sendit')."</h2>";
    
    
    //estraggo le liste
    $table_liste =  $wpdb->prefix . "nl_liste";   
    $liste = $wpdb->get_results("SELECT id_lista, nomelista FROM $table_liste ");
   // print_r($_POST);


   
   
    echo "<div class=\"table\">
			<table class=\"widefat  fixed\">
				<thead>
					<tr>
						<th>".__('id', 'sendit')."</th>
						<th class=".$css_list.">".__('mailing list', 'sendit')."</th>
						<th>".__('actions', 'sendit')."</th>

					</tr>
				</thead><tbody>";

            foreach ($liste as $lista) {

                if ($_GET['lista']==$lista->id_lista) : $selected=" class=\"updated fade\"";  else : $selected=""; endif;     

                echo "<tr >
                		<td>".$lista->id_lista."</td>
                		<td ".$selected."><a class=\"\" href=\"admin.php?page=lista-iscritti&lista=".$lista->id_lista."\">".$lista->nomelista."</a></td>
                		<td></td><tr>";
            }
        echo"</tbody></table>
        </div><br clear=\"all\ />";
    
    /*miglioro facendo comparire la form x aggiungere solo se selezionata una lista*/
    if ($_GET['lista']) :
        
        echo "<h3>".__('Manual Subscribe mailing list ', 'sendit')." ".$_POST['lista']."</h3>

                <label for=\"email_add\">".__('email address (one or more: default separator= line break)', 'sendit')."<br />
               <div id=\"dashboard-widgets\" class=\"metabox-holder\">
               <div class='postbox-container' style='width:49%;'>
				<div id=\"normal-sortables\" class=\"meta-box-sortables\">
				<div id=\"dashboard_right_now\" class=\"postbox \" >
					<div class=\"handlediv\" title=\"Fare clic per cambiare.\"><br /></div>
				<h3 class='hndle'><span>".__('Subscription','sendit')."</span></h3>
				<div class=\"inside\">
				        <p>".__('Copy here one or more email address', 'sendit')."</p>

					           <form id=\"add\" name=\"add\" method=\"post\" action=\"admin.php?page=lista-iscritti&lista=".$_GET[lista]."\">

                
                <textarea id=\"emails_add\" type=\"text\" value=\"\" name=\"emails_add\" rows=\"10\" cols=\"50\"/></textarea></label>
                 <input type=\"hidden\" name=\"id_lista\" value=\"".$_GET[lista]."\" /> 

                <input class=\"button\" type=\"submit\" value=\"".__('Add', 'sendit')."\"/>
                </p>
                            </form>
                </div>
               </div>
               </div>
               </div>
               </div>
               <br clear=\"all\" />";
        //posiziono la paginazione

		echo "<h3>".__('Subscribers', 'sendit')." n.".$email_items." (".__('Subscriptions confirmed', 'sendit').": ".count($emails_confirmed).")</h3>";
       if($p):
			echo $p->show();
		endif;

        
        echo "
        <br clear=\"all\" />
			<table class=\"widefat post fixed\">
				<thead>
					<tr>
						<th style=\"width:30px !important;\"></th>
						<th>".__('email', 'sendit')."</th>
						<th>".__('status', 'sendit')."</th>
						<th>".__('Additional info', 'sendit')."</th>
						<th>".__('actions', 'sendit')."</th>

					</tr>
				</thead>
    	
        ";
        
      
        foreach ($emails as $email) {
            
            //coloro le input per distinguere tra chi ha confermato e chi no
            if ($email->accepted=="y") { $style="style=\"background:#E4FFCF; border:1px solid #B6FF7F;\""; }
            elseif ($email->accepted=="n") { $style="style=\"background:#fffbcc; border:1px solid #e6db55;\""; }
            else { $style="style=\"background:#fd919b; border:1px solid #EF4A5C;\""; }
            
        	//fare funzione per ricaare i valori ovunque            
        	$subscriber_info= json_decode($email->subscriber_info);  
        	$subscriber_options = explode("&", $subscriber_info->options);
        	$options='';
        
        	foreach($subscriber_options as $option):
        		$option=explode("=", $option);
        		//stampo solo i campi unserializzati senza email_add e lista
        		if($option[0]!='email_add' and $option[0]!='lista'):
        			$options.=$option[0];
        			$options.=':';
        			$options.=urldecode($option[1]);
        			$options.='<br />';
        		endif;

        	endforeach;
        
             
        echo "<tr>	
        		<form action=\"#email_".$email->id_email."\" method=\"post\">
				<td class=\"grav\" style=\"width:30px !important;\">".get_avatar($email->email,'24')."</td>
                <td id=\"email_".$email->id_email."\">
                   
                        <!--input type=\"checkbox\" name=\"email_handler[]\" value=\"".$email->id_email."\">-->
                        <input type=\"hidden\" name=\"id_email\" value=\"".$email->id_email."\">
                        <input type=\"hidden\" name=\"lista\" value=\"".$_POST['lista']."\">
                        <input type='hidden' name='code' value='".$email->magic_string."' />
                        <input type='text' name='email' value='".$email->email."' />
                        </td>
                        <td   ".$style." >
                        <select name=\"status\">
                            
                            <option value=\"y\"";
                            
                            if ($email->accepted=="y") { echo " selected=\"selected\""; }
                            
                            echo">".__('Confirmed', 'sendit')."</option>
                            <option value=\"n\"";
                            
                            if ($email->accepted=="n") { echo " selected=\"selected\""; }
                            echo">".__('Not confirmed', 'sendit')."</option>
                            <option value=\"d\"";
                            
                            if ($email->accepted=="d") { echo " selected=\"selected\""; }
                            echo">".__('Cancelled', 'sendit')."</option>
                            
                        </select>
                        </td>
                       <td>".$options."</td>

                        <td>
	                        <input type=\"submit\" class=\"button\" name=\"update\" value=\"".__('Update', 'sendit')."\">
	                        <input type=\"submit\" class=\"button\" name=\"delete\" value=\"".__('Delete', 'sendit')."\">
						</td>
            </form>
            </tr>    ";
            
        
        }
    
    
    
    echo "		<tfoot>
					<tr>
						<th style=\"width:30px !important;\"></th>
						<th>".__('email', 'sendit')."</th>
						<th>".__('status', 'sendit')."</th>
						<th>".__('Additional info', 'sendit')."</th>
						<th>".__('actions', 'sendit')."</th>
					</tr>
				</tfoot>
</table><br clear=\"all\" />";
    //ripeto la paginazione
    if($p):
			echo $p->show();
	endif;
    
    endif;    
    
    echo "</div>";
  
    
}

function gestisci_menu() {
/*++++++++++++++++Menu Handler+++++++++++++++++++++++++++++++*/
	global $wpdb;   
    add_menu_page(__('Send', 'sendit'), __('Sendit', 'sendit'), 8, __FILE__, 'MainSettings');
    add_submenu_page(__FILE__, __('Manage subscribers', 'sendit'), __('Manage subscribers', 'sendit'), 8, 'lista-iscritti', 'Iscritti');
    add_submenu_page(__FILE__, __('List Options', 'sendit'), __('Lists management', 'sendit'), 8, 'lists-management', 'ManageLists');   
    add_submenu_page(__FILE__, __('Widget settings', 'sendit'), __('Widget settings', 'sendit'), 8, 'sendit_widget_settings', 'SenditWidgetSettings');

	/*2.0 export addon*/
	if (function_exists('sendit_morefields')) 
	{
		add_submenu_page(__FILE__, __('Fields settings', 'sendit'), __('Fields settings', 'sendit'), 8, 'sendit_morefields_settings', 'SenditMoreFieldSettings');
	}
	else
	{
		add_submenu_page(__FILE__, __('Fields list', 'sendit'), __('Fields settings', 'sendit'), 8, 'sendit_morefields_settings', 'sendit_morefields_screen');
	}
 

    add_submenu_page(__FILE__, __('SMTP settings', 'sendit'), __('SMTP settings', 'sendit'), 8, 'sendit_smtp_settings', 'SmtpSettings');  
    
    add_submenu_page(__FILE__, __('email import', 'sendit'), __('Import emails from comments', 'sendit'), 8, 'mass-import', 'ImportWpComments');
    
    
    add_submenu_page(__FILE__, __('email import', 'sendit'), __('Import emails from WP Users', 'sendit'), 8, 'import', 'ImportWpUsers');

 
	if ($wpdb->get_var("show tables like 'bb_press'") != '') :
		add_submenu_page(__FILE__, __('email import', 'sendit'), __('Import emails from BBpress', 'sendit'), 8, 'import-bb-users', 'ImportBbPress');
	endif;
	
	if (function_exists('sendit_check')) {
	    add_submenu_page(__FILE__, __('Cron Settings', 'sendit'), __('cron settings', 'sendit'), 8, 'cron-settings', 'cron_settings');
	}
	
	/*1.5.7 export addon*/
	if (function_exists('sendit_csv_export')) 
	{
		add_submenu_page(__FILE__, __('Export list', 'sendit'), __('Export list', 'sendit'), 8, 'export-subscribers', 'export_subscribers');
	}
	else
	{
		add_submenu_page(__FILE__, __('Export list', 'sendit'), __('Export list', 'sendit'), 8, 'export-subscribers', 'export_subscribers_screen');
	}

    
    

}

?>