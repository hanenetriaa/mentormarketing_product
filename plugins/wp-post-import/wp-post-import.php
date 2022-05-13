<?php
/**
 * Plugin Name: WP Post Import
 * Author: Kudosta Solutions LLP
 * Version: 1.0
 * Plugin URI: 
 * Description: WP Post Import is an extremely powerful importer that makes it easy to import any Excel or CSV file to WordPress.
 * Author: Kudosta
 * Author URI: https://www.kudosta.com/
 * Requires at least: 4.8
 * Tested up to: 5.2.1 
 * Text Domain: WP Post Import
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class WP_Post_Import_Setting { 

    function __construct() 
    {   
        add_action( 'admin_enqueue_scripts',  array( $this, 'wppi_plugin_scripts' ));      

        add_action('admin_menu',  array( $this,'wppi_add_import_menu'));  

        add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this,'wppi_plugin_action_links'));

        add_action('wp_ajax_wppi_show_data',  array( $this,'wppi_show_data'));

        add_action('wp_ajax_nopriv_wppi_show_data',  array( $this, 'wppi_show_data'));
    }  

    public function wppi_plugin_scripts($hook) 
    { 
        if($hook == 'toplevel_page_import-plugin')
        { 
            wp_enqueue_script('jquery-effects-core');

            wp_enqueue_script( 'wppissjs', plugin_dir_url(__FILE__) . 'assets/js/xlsx.full.min.js', array( 'jquery' ));
            wp_enqueue_style( 'wppicss', plugin_dir_url(__FILE__) . 'assets/css/style.css');
            wp_enqueue_script( 'wppijs-ajax', plugin_dir_url(__FILE__) . 'assets/js/wppostimport.js', array( 'jquery' ));
            wp_localize_script( 'wppijs-ajax', 'wppijs_ajax_object',
                array( 
                    'ajaxurl' => admin_url('admin-ajax.php'), 
                    'ajax_nonce' => wp_create_nonce('show_data'),
                )
            );     
        } 
    } 

    /*
    * adding setting menu with deactive menu below plugin.
    */
   public function wppi_plugin_action_links( $links ) {
        $links = array_merge( array(
            '<a href="' . esc_url( admin_url( 'admin.php?page=import-plugin' ) ) . '">' . __( 'Settings', 'wp-post-import' ) . '</a>'
        ), $links );
        return $links;
    }

    /*
    * Admin menu setting.
    */
    public function wppi_add_import_menu()
    {
        add_menu_page( 'WP Post Import', 'WP Post Import', 'manage_options', 'import-plugin', array( $this, 'wppi_import_file'),plugin_dir_url(__FILE__). 'assets/images/import-icon.png');
    }

    /*
    * Import csv, xlsx file functionality .
    */

    public function wppi_import_file()
    {   
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');       
        include plugin_dir_path(__FILE__) . 'template/post_import_function.php';
        /*
        * Import form submit  .
        */ 
        if(isset($_POST['wppi_submit']))
        { 
            if (! isset( $_POST['wppi_noncefield'] )  || ! wp_verify_nonce( $_POST['wppi_noncefield'], 'wppi_submit_form' ) ) 
            {
                $wppi_nonce_err = "There is some problem in the import file, please try again!"; 
            }
            else
            {
                global $wpdb,$success;
                                         
                foreach ($_POST as $key => $value)
                { 
                    if( $counter == count( $_POST ) - 1 )
                    {
                        break;
                    }
                    $store = sanitize_text_field($_POST['page_id']);

                    $field_data[$key]=$value;
                }

                $update = sanitize_text_field($_POST['optfile']);

                $path = sanitize_file_name($_FILES['import_file']['name']);

                $info = new SplFileInfo($path);

                $pdf = $info->getExtension();
                /*
                * Create posts by csv,xlsx file .
                */               

                if($update == 'new')
                {   
                    /*
                    * Create posts by csv file .
                    */
                    if($pdf == "csv")
                    { 
                        if(!empty($path))
                        { 
                            if(is_uploaded_file($_FILES['import_file']['tmp_name']))
                            { 
                                //open uploaded csv file with read only mode
                                $csvFile = fopen($_FILES['import_file']['tmp_name'], 'r'); 

                                $heading_file = fgetcsv ( $csvFile, 5000 ); 
                            }

                            $medaya = array();

                            foreach ($field_data as $kkey => $vvalue) 
                            {
                                if ( in_array($vvalue, $heading_file) )
                                {

                                    $key = array_search($vvalue, $heading_file);
                                    $medaya[$kkey] = $key;
                                }
                            }

                            $post_title_no = $medaya['post_title'];

                            $post_content_no = $medaya['post_content'];

                            $post_image_no = $medaya['feature_image_url'];                           

                            $i=1;
                            //parse data from csv file line by line
                            $post_insert = 0;
                            $post_not_insert = 0;
                            while(($line = fgetcsv($csvFile)) !== FALSE)
                            {   
                                $post_title_new = $line[$post_title_no ];

                                $post_content_new = $line[$post_content_no];

                                $post_image_new = $line[$post_image_no];

                                if($post_content_new == "")
                                {
                                    $post_content_new = "";
                                }

                                if($post_title_new == "")
                                {
                                    $post_title_new = "";
                                }                         

                                $file = $post_image_new;

                                $pid = wppi_insert_post($post_title_new,$post_content_new,$store);
                                if (isset($pid) && $pid > 0) 
                                {
                                	$post_insert++;
                                }
                                else
                                {
                                	$post_not_insert++;
                                }
                                if($file != '')
                                {
                                    $image = media_sideload_image($file, $pid, $desc, 'id');
                                    set_post_thumbnail( $pid, $image );
                                }                        

                                $i++;
                            }

                            fclose($csvFile);
                            if(!empty($post_insert) && $post_insert > 0)
		                    { 
		                    	$post_not_insert_msg = '';
		                    	if (isset($post_not_insert) && $post_not_insert != '') 
		                    	{
		                    		$post_not_insert_msg = '<p>'.$post_not_insert.' Posts not inserted</p>';
		                    	}
		                        $success = '<div id="message" class="updated notice is-dismissible"><p>'.$post_insert.' Posts import Successfully!</p>'.$post_not_insert_msg.'<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>'; 
		                    }
		                    else
		                    {
		                        $success = '<div id="message" class="updated notice is-dismissible"><p>No Post imported!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
		                    } 
                        }
                    } 
                    /*
                    * Create posts by xlsx file .
                    */
                    elseif ($pdf == "xlsx" || $pdf == "xls") 
                    {
                        if(isset($_FILES['import_file']) && $_FILES['import_file']['error']==0) 
                        {
                            require_once "Classes/PHPExcel.php";

                            $tmpfname = $_FILES['import_file']['tmp_name'];

                            $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);

                            $excelObj = $excelReader->load($tmpfname);

                            $worksheet = $excelObj->getSheet(0);

                            $lastRow = $worksheet->getHighestRow();

                            $lastColumn = $worksheet->getHighestColumn();

                            $head = array();

                            for ($row = 1; $row <= $lastRow; $row++) 
                            {
                                if($row == 1)
                                {

                                 for($col = 'A'; $col <= $lastColumn; $col++)
                                    {
                                        $head[] = $worksheet->getCell($col.$row)->getValue();
                                    }

                                    break;
                                }   
                            }

                            $medaya = array();

                            foreach ($field_data as $kkey => $vvalue) 
                            {
                                if ( in_array($vvalue, $head) ) 
                                {
                                    $key = array_search($vvalue, $head);

                                    $medaya[$kkey] = $key;
                                }
                            }
                          
                            $ch = array();

                            foreach ($medaya as $kkey => $num)
                            {

                                $numeric = $num % 26;

                                $letter = chr(65 + $numeric);

                                $num2 = intval($num / 26);

                                if ($num2 > 0) 
                                {

                                    $ch[] = getNameFromNumber($num2 - 1) . $letter;
                                } 

                                else 
                                {
                                    $ch[] = $letter;

                                }
                            }

                            $mkey = array_keys($medaya);

                            $new = array_combine($mkey, $ch);

                            $post_title_no = $new['post_title'];

                            $post_content_no = $new['post_content'];

                            $post_image_no = $new['feature_image_url'];

                            $post_insert = 0;
                            for ($row = 1; $row <= $lastRow; $row++) 
                            { 
                                if($row != 1)
                                { 
                                    if($post_title_no != "")
                                    {
                                    $post_title_new = $worksheet->getCell($post_title_no.$row)->getValue();
                                    }
                                    if($post_content_no != "")
                                    {
                                    $post_content_new = $worksheet->getCell($post_content_no.$row)->getValue();
                                    }
                                    if($post_image_no != "")
                                    {
                                    $post_image_new = $worksheet->getCell($post_image_no.$row)->getValue();
                                    }

                                    if($post_content_new == "")
                                    { 
                                        $post_content_new = ""; 
                                    }

                                    if($post_title_new == "")
                                    { 
                                        $post_title_new = ""; 
                                    }

                                    $file = $post_image_new;

                                    $pid = wppi_insert_post($post_title_new,$post_content_new,$store);
                                    if (isset($pid) && $pid > 0) 
	                                {
	                                	$post_insert++;
	                                }
                                    if($file != '')
                                    { 
                                        $image = media_sideload_image($file, $pid, $desc, 'id');
                                        set_post_thumbnail( $pid, $image );
                                    }
                                }   
                            }

                            if(!empty($post_insert) && $post_insert > 0)
		                    { 
		                        $success = '<div id="message" class="updated notice is-dismissible"><p>'.$post_insert.' Posts import Successfully!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>'; 
		                    }
		                    else
		                    {
		                        $success = '<div id="message" class="updated notice is-dismissible"><p>No Post imported!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
		                    } 
                        }   
                    }
                    else
                    { 
                        $success = '<div id="message" class="error notice is-dismissible"><p>Please upload CSV or xlsx file!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                    }  
                }
                /*
                * update posts by csv,xlsx file .
                */
                elseif($update == 'existing')
                {
                   /*
                    * update posts by csv file .
                    */
                    if($pdf == "csv")
                    { 
                        if(!empty($path))
                        { 
                            if(is_uploaded_file($_FILES['import_file']['tmp_name']))
                            { 
                                //open uploaded csv file with read only mode 
                                $csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');  
                                $heading_file = fgetcsv ( $csvFile, 5000 );  
                            }
                            $medaya = array();

                            foreach ($field_data as $kkey => $vvalue) 
                            { 
                                if ( in_array($vvalue, $heading_file) )
                                { 
                                    $key = array_search($vvalue, $heading_file); 
                                    $medaya[$kkey] = $key;
                                }
                            }
                            
                            $update_id = $medaya['update_post']; 
                            $post_title_no = $medaya['post_title']; 
                            $post_content_no = $medaya['post_content']; 
                            $post_image_no = $medaya['feature_image_url'];   
                            $i=1;

                            //parse data from csv file line by line
                            
                            $post_insert = 0;
                            while(($line = fgetcsv($csvFile)) !== FALSE)
                            {   
                                $update_id_file = $line[$update_id];    
                                $post_title_new = $line[$post_title_no ]; 
                                $post_content_new = $line[$post_content_no]; 
                                $post_image_new = $line[$post_image_no];

                                if($post_content_new == "")
                                { 
                                    $post_content_new = ""; 
                                }

                                if($post_title_new == "")
                                { 
                                    $post_title_new = ""; 
                                }

                                $file = $post_image_new;

                                if(is_numeric($update_id_file) && !empty($update_id_file))
                                {
                                    $results_update = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish' and ID = '$update_id_file' ", $store ), ARRAY_A );
                                }
                                else
                                {
                                    $results_update = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish' and post_title = '$post_title_new'", $store ), ARRAY_A );
                                }
                                                                
                                if ( count($results_update) > 0)
                                {  
                                    foreach ($results_update as $results1) 
                                    { 
                                        $pid = $results1['ID'];  

                                        wppi_update_post($pid,$post_title_new,$post_content_new);

                                        if($file != '')
                                        {
                                            $post_thumbnail_id = get_post_thumbnail_id($pid); 

                                            wp_delete_attachment($post_thumbnail_id, true);

                                            $image = media_sideload_image($file, $pid, $desc, 'id');

                                            set_post_thumbnail( $pid, $image );
                                        } 
                                    }

									$post_insert++; 
                                }
                                         
                                $i++;
                            }

                            fclose($csvFile);
                            if(!empty($post_insert) && $post_insert > 0)
							{ 
							    $success = '<div id="message" class="updated notice is-dismissible"><p>'.$post_insert.' Posts updated Successfully!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>'; 
							}
							else
							{
							    $success = '<div id="message" class="updated notice is-dismissible"><p>No Post updated!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
							}
                        }
                    } 
                      /*
                    * update posts by xlsx file .
                    */
                    elseif ($pdf == "xlsx" || $pdf == "xls") 
                    {

                        if(isset($_FILES['import_file']) && $_FILES['import_file']['error']==0)
                        {

                            require_once "Classes/PHPExcel.php";

                            $tmpfname = $_FILES['import_file']['tmp_name'];

                            $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);

                            $excelObj = $excelReader->load($tmpfname);

                            $worksheet = $excelObj->getSheet(0);

                            $lastRow = $worksheet->getHighestRow();

                            $lastColumn = $worksheet->getHighestColumn();

                            $head = array();

                            for ($row = 1; $row <= $lastRow; $row++) 
                            {

                                if($row == 1)
                                {

                                 for($col = 'A'; $col <= $lastColumn; $col++)
                                    {

                                        $head[] = $worksheet->getCell($col.$row)->getValue();

                                    }
                                    break;
                                }   
                            }    
                

                            $medaya = array();

                            foreach ($field_data as $kkey => $vvalue) 
                            {

                                if ( in_array($vvalue, $head) ) 
                                {

                                    $key = array_search($vvalue, $head);

                                    $medaya[$kkey] = $key;

                                }
                            }

                            $ch = array();

                            foreach ($medaya as $kkey => $num) 
                            {

                                $numeric = $num % 26;

                                $letter = chr(65 + $numeric);

                                $num2 = intval($num / 26);

                                if ($num2 > 0) 
                                {

                                    $ch[] = getNameFromNumber($num2 - 1) . $letter;

                                } 
                                else 
                                {
                                    $ch[] = $letter;
                                }
                            }

                            $mkey = array_keys($medaya);

                            $new = array_combine($mkey, $ch);

                            $update_id = $new['update_post'];

                            $post_title_no = $new['post_title'];

                            $post_content_no = $new['post_content'];

                            $post_image_no = $new['feature_image_url'];
                            $post_insert = 0;
                            for ($row = 1; $row <= $lastRow; $row++)
                            {
                                if($row != 1)
                                { 
                                    if($post_title_no != "")
                                    {
                                    $post_title_new = $worksheet->getCell($post_title_no.$row)->getValue();
                                    }
                                    if($post_content_no != "")
                                    {
                                    $post_content_new = $worksheet->getCell($post_content_no.$row)->getValue();
                                    }
                                    if($post_image_no != "")
                                    {
                                    $post_image_new = $worksheet->getCell($post_image_no.$row)->getValue();
                                    }

                                    if($update_id !="")
                                    {
                                        $update_id_file = $worksheet->getCell($update_id.$row)->getValue();
                                    }

                                    $file = $post_image_new;

                                    $image_name = basename($file);

                                    if($post_content_new == "")
                                    {

                                        $post_content_new = "";

                                    }

                                    if($post_title_new == "")
                                    {

                                        $post_title_new = "";

                                    }
                                    if(is_numeric($update_id_file) && !empty($update_id_file))
                                    {
                                        $results_update = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish' and ID = '$update_id_file' ", $store ), ARRAY_A );
                                    }
                                    else
                                    {
                                        $results_update = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish' and post_title = '$post_title_new'", $store ), ARRAY_A );                                        
                                    }

                                    if ( count($results_update) > 0)
                                    { 

                                        foreach ($results_update as $results1) 
                                        {
                                            $pid = $results1['ID'];

                                            wppi_update_post($pid,$post_title_new,$post_content_new);


                                            if($file != '')  
                                            {
                                                $post_thumbnail_id = get_post_thumbnail_id($pid); 

                                                wp_delete_attachment($post_thumbnail_id, true);

                                                $image = media_sideload_image($file, $pid, $desc, 'id');

                                                set_post_thumbnail( $pid, $image );

                                            }
                                        }
                                        $post_insert++;
                                    }
                                }   
                            }
                            if(!empty($post_insert) && $post_insert > 0)
							{ 
							    $success = '<div id="message" class="updated notice is-dismissible"><p>'.$post_insert.' Posts updated Successfully!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>'; 
							}
							else
							{
							    $success = '<div id="message" class="updated notice is-dismissible"><p>No Post updated!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
							}
                        } 
                    }
                    else
                    {
                        $success = '<div id="message" class="error notice is-dismissible"><p>Please upload CSV or xlsx file!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>'; 
                    } 
                } 
            } 
        }

        /*
        * Import form layout files Include .
        */
        include plugin_dir_path(__FILE__) . 'template/post_import_form.php';
       
    }

    /*
    * posts fileds list show 
    */  
    public function wppi_show_data()

    {
        check_ajax_referer( 'show_data', 'security' );

        $update_value_post = $_POST['update_value_post'];

        global $wpdb;

        $response .='<script> var acc = document.getElementsByClassName("accordion");
                    var i;

                    for (i = 0; i < acc.length; i++) {
                      acc[i].addEventListener("click", function() {
                        this.classList.toggle("active");
                        var panel = this.nextElementSibling;
                        if (panel.style.display === "block") {
                          panel.style.display = "none";
                        } else {
                          panel.style.display = "block";
                        }
                      });
                    }</script>'; 
   
        if($update_value_post == 'existing')
        {
            $response .='<input type ="text" name="update_post" placeholder="Update Post by ID/ post_title"><br>';
        }      
        $response .='<input type="text" name="post_title" class="post-title" placeholder="Post Title"><br>'; 
        $response .='<span class="title_error">Please Enter Post Title.</span>';

        $response .='<textarea name="post_content" placeholder="Post Content"></textarea><br>'; 
        $response .='<input type="text" name="feature_image_url" placeholder="Featured Image"><br>'; 
        $response .='<button class="accordion" type="button">Post Category</button>
            <div class="panel">
              <p>This feature available in pro version !</p>
            </div>';
         $response .='<button class="accordion" type="button">Custom Fields</button>
            <div class="panel">
              <p>This feature available in pro version !</p>
            </div>';   
        echo wp_json_encode($response);

        exit();
    }
}
$wpDribbble = new WP_Post_Import_Setting();
?>