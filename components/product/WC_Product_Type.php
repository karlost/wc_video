<?php 
defined( 'ABSPATH' ) || exit;
if( ! class_exists( 'WC_Restricted_Video' ) )
    {

        class WC_Restricted_Video extends WC_Product 
        {
            public function __construct($product){
                $this->product_type = 'restricted_video';
                parent::__construct($product);
            }
        }
        
    }