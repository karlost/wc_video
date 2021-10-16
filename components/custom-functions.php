<?php
/**
 * ziskává SVG soubor 
 *
 * @param url
 * 
 * @author Wedesin
 * @return 
 */ 

     if( ! function_exists('wds_get_svg') ){
            function wds_get_svg($url) {
                $arrContextOptions=array(
                    "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                );  
                
                $response = file_get_contents($url , false, stream_context_create($arrContextOptions));
                return $response;

             }
        }
    function video_src() { 
        if( get_field('_restricted_demo_url') && get_field('_restricted_video_url') ) {
            $demo = get_field('_restricted_demo_url');
            $video = get_field('_restricted_video_url');
            $have = new videoRestrictedProduct;
        // echo '<script>
        // document.getElementsByClassName("woocommerce-product-gallery__image--placeholder")[0].style.visibility="hidden";
        // document.getElementsByClassName("woocommerce-product-gallery__image--placeholder")[0].style.position="absolute";
        // </script>';
        $items = WC()->cart->get_cart();
        if( $items ){
            foreach($items as $item => $values) {
                $product = wc_get_product( $values['product_id'] );
                if( $product->get_type() == 'restricted_video' ){
                   
                 echo 'true';
                }
               $neco =  $product->get_type();
                echo $values['product_id'];
                preprint ($neco);
            }
        }
       

        $test = $have->check_cart_before_checkout_for_registration();
        if ($test > 0 ){
            echo 'true';
        }else {
            echo 'false';
        }
        if($have->has_bought_items('0',$product_id) == false ){

            echo  '<iframe  src="'.$demo.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
       
        } else {
            echo  '<iframe  src="'.$video.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        
        }
       
       
    } 
        
        
};     
    add_action( 'woocommerce_product_thumbnails', 'video_src', 100 );
?>