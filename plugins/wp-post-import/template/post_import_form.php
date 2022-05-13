<?php 
   /*
    * Form templete 
    */ 
?> 
<div class="wppi_import_form">  
      <?php
        if(isset($success) && $success != '')
        {
          echo $success;
          $success = '';
        }
      ?>
     <h2 class="main-heading"> Import XLSX / CSV </h2> 
     <!-- multistep form -->    
     
    <form id="msform" method="post" action="" enctype="multipart/form-data" class="data_import_form" style="">

      <?php wp_nonce_field( 'wppi_submit_form', 'wppi_noncefield' ); ?>

      <!-- progressbar -->

      <ul id="progressbar">

        <li class="active"><?php _e('Choose action', get_current_theme() ); ?></li>

        <li><?php _e('Upload file', get_current_theme() ); ?></li>

        <li><?php _e('Select Post Type', get_current_theme() ); ?></li>

        <li><?php _e('Select Post Fields', get_current_theme() ); ?></li>

      </ul>

      <!-- fieldsets -->
      <fieldset>
        
        <h2 class="fs-title"><?php _e('Import data from file', get_current_theme() ); ?></h2>

        <h3 class="fs-subtitle"><?php _e('Specify, Import new data or update exisintg data', get_current_theme() ); ?></h3>

            <div class="password_outer">

                <div class="password-inner-change input-box"><input type="radio" name="optfile" checked value="new"></div>

                <div class="password-inner-change input-name"><?php _e('Insert New Posts', get_current_theme() ); ?></div>

            </div>

            <div style="clear: both;"></div>

            <div class="password_outer">

                <div class="password-inner-change input-box"><input type="radio" name="optfile" value="existing"></div>

                <div class="password-inner-change input-name"><?php _e('Update Existing Posts', get_current_theme() ); ?></div>

            </div>

            <div style="clear: both;"></div>

        <input type="button" name="next" class="next action-button" data-id="1" value="Next" />

      </fieldset>

      <fieldset>

        <h2 class="fs-title"><?php _e('Upload a file', get_current_theme() ); ?></h2>

        <h3 class="fs-subtitle"><?php _e('Import data from xlsx/csv', get_current_theme() ); ?></h3>

         <input type="file" name="import_file" id="csvimportk" accept=".csv, .xls, .xlsx">

         <span class="file_error">Please upload a valid file.</span>

         <input type="button" name="previous" class="previous action-button" value="Previous" />

        <input type="button" name="next" class="next nextfile action-button" id="checkfile" value="Next" data-id="2"/>

      </fieldset>

      <fieldset>

        <h2 class="fs-title"><?php _e('Select Post Type', get_current_theme() ); ?></h2>          

            <select name="page_id" id="page_id" class="post">

            <?php

             

            $removepost = array('attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block', 'acf-field-group', 'acf-field', 'vcv_templates','scheduled-action','product','product_variation','shop_order','shop_order_refund','shop_coupon' );

            foreach ( get_post_types() as $post_type ) 

            {

                if(!in_array($post_type, $removepost))

                { 

            ?>
                   <option value="<?php echo $post_type; ?>"><?php echo $post_type; ?></option>

                    <?php 
                }

            } 

            ?>

            </select>

        <input type="button" name="previous" class="previous action-button" value="Previous" />

        <input type="button" name="next" class="next action-button get_post_type" value="Next" data-id="3"/>

      </fieldset>

      <fieldset>

        <h2 class="fs-title"><?php _e('Select Post Fields', get_current_theme() ); ?></h2>
        <div id="custom_field_loder" style="display: none;">
          <img id="loader" src="<?php echo plugin_dir_url(__FILE__);?>../assets/images/ajax-loader.gif">
        </div>
        <div id="show_custom"></div>

        <input type="button" name="previous" class="previous action-button" value="Previous" /> 

        <input type ="submit" name="wppi_submit" class="action-button submit" id="cretapost" value="Submit" data-id="4"/> 

      </fieldset>      

    </form>

    <div class="csvdata"></div>
    
    <div class="clear"></div>
</div>    