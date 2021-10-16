<?php 
/**
 * Popis třídy
 *
 * 
 * @author Wedesin
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'videotourl' ) )
{
	class videotourl
	{
         private $video;
        //private $variable; 

		public function __construct()
		{
            do_action('woocommerce_before_single_product_summary', [$this,'video_url']);
            //set variable
            //$this->variable = '';
            
        }

        public function video_src() {
         
          if( get_field('video_url', 107)) {
             $video = get_field('video_url', 107); 
            }
  
            echo '<iframe  src="'.$video['video_url'].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            
            
             

        }
    }
     new videotourl;
}