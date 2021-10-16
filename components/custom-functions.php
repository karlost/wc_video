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
 
        if($have->has_bought_items('0',$product_id) == false ){

            echo  '<iframe  src="'.youtube_url($demo).'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
       
        } else {
            echo  '<iframe  src="'.youtube_url($video).'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        
        }
       
       
    } 
        
        
};   

    add_action( 'woocommerce_product_thumbnails', 'video_src', 100 );

    function youtube_url($url){
        if($url){
        $url_components = parse_url($url);
        $url = substr($url_components['query'],2);
        return 'https://www.youtube.com/embed/'.$url;
        }
   
    }
?>