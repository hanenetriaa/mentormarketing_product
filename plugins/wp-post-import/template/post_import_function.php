<?php 
/*
* posts Insert function return post id 
*/ 

function wppi_insert_post($post_title_new,$post_content_new,$store)
{
  if (isset($post_title_new) && $post_title_new != '') 
  {
   
    $new_post = array(
    'post_title'  => $post_title_new,
    'post_content'  => $post_content_new,
    'post_status'   => 'publish', 
    'post_type'     => $store, 
    );  
    $pid = wp_insert_post($new_post);
  }
  else
  {
    $pid = false;
  }
  return $pid;
}

/*
* posts update function
*/ 
function wppi_update_post($pid,$post_title_new,$post_content_new){

    $my_post = array(
      'ID'           => $pid,
      'post_title'   => $post_title_new,
      'post_content' => $post_content_new,
    );
    wp_update_post( $my_post, true );
}
?>