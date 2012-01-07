<?php 
/*******************************
Installation core
*******************************/
require('constants.php');
global $sendit_db_version;
global $wpdb;

$sendit_db_version = "2.0";



function sendit_install() {
   global $wpdb;
   global $sendit_db_version;
	/*
	++++++++++++++++++++++++++++
	Table: wp_nl_email
	++++++++++++++++++++++++++++
	*/   
   $table_email = $wpdb->prefix . "nl_email";
   $table_liste = $wpdb->prefix . "nl_liste";
     
   $sql_email = "CREATE TABLE " . SENDIT_EMAIL_TABLE . " (
	  		  	id_email int(11) NOT NULL AUTO_INCREMENT,
              	id_lista  int(11) default '1',
              	contactname varchar(250) default NULL,
              	email varchar(250) default NULL,
              	subscriber_info text default NULL,
              	magic_string varchar(250) default NULL,
              	accepted varchar(1) default 'n',
              	post_id mediumint(9) NULL,
              	ipaddress VARCHAR(255)   NULL,
            
               PRIMARY KEY  (`id_email`),
                           KEY `id_lista` (`id_lista`)
    );";
	/*
	++++++++++++++++++++++++++++
	Table: wp_nl_liste
	++++++++++++++++++++++++++++
	*/  
    $sql_liste = "CREATE TABLE ".SENDIT_LIST_TABLE." (
                  `id_lista` int(11) NOT NULL auto_increment,                  
                  `nomelista` varchar(250) default NULL,
                  `email_lista` varchar(250) default NULL,
                  `header` mediumtext NULL,
                  `footer` mediumtext NULL,
                   PRIMARY KEY  (`id_lista`)
                 );";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql_email);
   dbDelta($sql_liste);   
   
   
    
   add_option("sendit_db_version", $sendit_db_version);
}





function sendit_sampledata() {
   	/*
	++++++++++++++++++++++++++++
	inserimento lista 1 di test con dati di prova
	++++++++++++++++++++++++++++
	*/  
    global $wpdb;
    $header_default='<h1>'.get_option('blogname').'</h1>';
    $header_default.='<h2>newsletter</h2>';
    $footer_default='<p><a href="http://sendit.wordpressplanet.org">'.__('Newsletter sent by Sendit Wordpress plugin').'</a></p>';
    
    $rows_affected = $wpdb->insert(SENDIT_LIST_TABLE, array('nomelista' => 'Testing list','email_lista' => get_bloginfo('admin_email'), 'header' =>$header_default, 'footer'=>$footer_default) );
}
?>