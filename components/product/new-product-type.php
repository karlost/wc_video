<?php 
/**
 * Popis třídy
 *
 * 
 * @author Wedesin
 */ 

 // vyřešit v pluginu cesty k ostatním souborům
 // vře´yřešit propisování ceny
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'videoRestrictedProduct' ) && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ))))
{
    class videoRestrictedProduct
    {

        public function __construct()
        {
            //add_action('init', [$this, 'test']);
            add_filter( 'product_type_selector', [$this, 'custom_product_type'] );
            add_action( 'wp_loaded',[$this, 'register_product_type'] );
            add_filter( 'woocommerce_product_class', [$this, 'woocommerce_product_class'], 10, 2 );
            add_filter( 'woocommerce_product_data_tabs', [$this,'video_settings_tab'] );
            add_action( 'woocommerce_product_data_panels', [$this,'video_settings_tab_content'] );
            // ukládání polí v adminu
            add_action( 'woocommerce_process_product_meta', [$this, 'save_video_options_field'] );
            //vyhodit pole s údaji o dopravě
            add_filter( 'woocommerce_checkout_fields' ,[$this, 'override_checkout_fields'] );
            // povolit prodej
            add_filter( 'woocommerce_is_purchasable', [$this, 'return_true_woocommerce_is_purchasable'], 10, 2 );

            // přidání tlačítka add to cart na single product
            add_action( 'woocommerce_single_product_summary', [$this, 'video_product_template'], 60 );
        
            add_action( 'woocommerce_checkout_update_order_meta', [$this,'wc_register_guests'], 10, 2 );
            add_action( 'woocommerce_before_checkout_form', [$this,'mess_if_in_cart'], 30, 2 );

            add_action( 'woocommerce_after_order_notes', [$this,'confirm_password_checkout'], 10, 1 );
            add_action( 'woocommerce_checkout_update_order_meta', [$this,'save_password_confirm'] );

            add_action( 'woocommerce_after_checkout_validation', [$this,'confirm_password_validation'], 10, 2 );
            add_action('woocommerce_checkout_process', [$this,'checkout_field_password']);


        }
     
     
       

   
  
        public function test(){
           // die('jo');
        }
         /**
         * registrace nového typu produktu
         * 
         * 
         *
         * 
         */
     
        public function custom_product_type($types){
            $types[ 'restricted_video' ] = 'Prodejní video';
            return $types;
        }

         /**
         * 
         * přidání classy k produktu
         * 
         *
         * 
         */
        
        public function woocommerce_product_class( $classname, $product_type ) {
            if ( $product_type == 'restricted_video' ) {
                $classname = 'WC_Restricted_Video';
              

            }
            return $classname;
        }

      
         /**
         * Registering video product type in product post woocommerce
         */
        public function register_product_type() {
            
            require_once ( TM_PATHWCVIDEO . 'components/product/WC_Product_Type.php');
        }

         /**
         * 
         *  přidání tabu s nastavením videa
         * 
         *
         * 
         */
        
        function video_settings_tab( $tabs) {
            // Key should be exactly the same as in the class product_type
            $tabs['restricted_video'] = array(
                'label'	 => __( 'Nastavení videa', 'wcpt' ),
                'target' => 'restricted_video_options',
                'class'  => ('show_if_restricted_video'),
                'priority' => 11,
            );
            /*$add_class = $tabs['general']['class'];
            array_push($add_class, 'show_if_restricted_video' );
            $tabs['general']['class'] = $add_class;*/
            return $tabs;
        }

          /**
         * 
         *  přidání tabu s nastavením videa - obsah
         * 
         *
         * 
         */
        
       
        function video_settings_tab_content() {
            
            ?>
            <div id='restricted_video_options' class='panel woocommerce_options_panel'>
                <div class='options_group'><?php
                    

                    woocommerce_wp_text_input( array(
                        'id'          => '_regular_price',
                        'label'       => __( 'Běžná cena', 'wcpt' ),
                        'placeholder' => '',
                        'desc_tip'    => 'false',
                    ));
                    woocommerce_wp_text_input( array(
                        'id'          => '_sale_price',
                        'label'       => __( 'Cena ve slevě', 'wcpt' ),
                        'placeholder' => '',
                        'desc_tip'    => 'false',
                    ));

                    woocommerce_wp_text_input( array(
                            'id'          => '_restricted_video_url',
                            'label'       => __( 'Url videa', 'wcpt' ),
                            'placeholder' => '',
                            'desc_tip'    => 'true',
                            'description' => __( 'Vložte url adresu videa k přehrání', 'wcpt' ),
                    ));
                    woocommerce_wp_text_input( array(
                    'id'          => '_restricted_demo_url',
                    'label'       => __( 'Url demovidea', 'wcpt' ),
                    'placeholder' => '',
                    'desc_tip'    => 'true',
                    'description' => __( 'Vložte url adresu demo videa k přehrání', 'wcpt' ),
                    ));
                    woocommerce_wp_select( array(
                        'id'          => '_tax_status',
                        'label'       => __( 'Stav daně', 'wcpt' ),
                        'options'     => array(
                            'taxable'  => __( 'Zdanitelný', 'wcpt' ),
                            'shipping' => __( 'Pouze poštovné', 'wcpt' ),
                            'none'     => _x( 'Žádný', 'Tax status', 'wcpt' ),
                        ),
                        'desc_tip'    => 'true',
                        'description' => __( 'Určete, zda je zdanitelné celé zboží, nebo jen náklady na jeho dopravu.', 'wcpt' ),
                    ));
                    woocommerce_wp_select( array(
                        'id'          => '_tax_class',
                        'label'       => __( 'Daňová třída', 'wcpt' ),
                        'options'     => array(
                            'taxable'  => __( 'Standardní', 'wcpt' ),
                            'shipping' => __( ' Reduced rate', 'wcpt' ),
                            'none'     => _x( 'Zero Rate', 'Tax status', 'wcpt' ),
                        ),
                        'desc_tip'    => 'true',
                        'description' => __( 'Vyberte daňovou třídu produktu. Daňové třídy se používají pro aplikování různých daňových sazeb na různé druhy produktů.', 'wcpt' ),
                    ));


                    
                ?>
                </div>
            </div>
            <?php
        }


          /**
         * 
         *  odstranění polí z košíku
         * 
         *
         * 
         */
    
        function override_checkout_fields( $fields ) {
            $items_count = 0;
            $found = 0;
            $items = WC()->cart->get_cart();
            if( $items ){
                foreach($items as $item => $values) {
                    $product = wc_get_product( $values['product_id'] );
                    if( $product->get_type() == 'restricted_video' ){
                        $found++;
                     
                    }
                    $items_count++;
                }
            }
            if( $items_count == $found ) {

                unset($fields['shipping']['shipping_first_name']);    
                unset($fields['shipping']['shipping_last_name']);  
                unset($fields['shipping']['shipping_company']);
                unset($fields['shipping']['shipping_address_1']);
                unset($fields['shipping']['shipping_address_2']);
                unset($fields['shipping']['shipping_city']);
                unset($fields['shipping']['shipping_postcode']);
                unset($fields['shipping']['shipping_country']);
                unset($fields['shipping']['shipping_state']);
                unset($fields['shipping']); 

                add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false');
            }
            return $fields;
        }

          
          
         /**
         * 
         *  uloží do post
         * 
         *
         * 
         */

        function save_video_options_field( $post_id ) {
        
            if ( isset( $_POST['_restricted_demo_url'] ) )
                update_post_meta( $post_id, '_restricted_demo_url', sanitize_text_field( $_POST['_restricted_demo_url'] ) );
            if ( isset( $_POST['_restricted_video_url'] ) )
                update_post_meta( $post_id, '_restricted_video_url', sanitize_text_field( $_POST['_restricted_video_url'] ) );
        }

         /**
         * @param $purchasable
         * @param $product
         * @return bool
         *
         * Return true is purchasable if not found price
         */

        function return_true_woocommerce_is_purchasable( $purchasable, $product ){
            if( $product->get_type() == 'restricted_video'){
                $purchasable = true;
              
            }
            return $purchasable;
        }

         /**
         * 
         *  definice cesty k šabloně
         * 
         *
         * 
         */
        
        function video_product_template () {

            global $product;
            if ( 'restricted_video' == $product->get_type() ) {
                do_action( 'video_product_before_add_to_cart_form' );
            ?>
            <form class="video_product_cart" method="post" enctype='multipart/form-data'>
                <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
            </form>
            <?php
                do_action( 'video_product_after_add_to_cart_form' );
            }
        }



/**
         * 
         *  kontrola pokud ma uzivatel produkt zakoupený
         * 
         *
         * 
         */
        function has_bought_items( $user_var = 0,  $product_ids = 0 ) {
            global $wpdb;
            
            // Based on user ID (registered users)
            if ( is_numeric( $user_var) ) { 
                $meta_key     = '_customer_user';
                $meta_value   = $user_var == 0 ? (int) get_current_user_id() : (int) $user_var;
            } 
            // Based on billing email (Guest users)
            else { 
                $meta_key     = '_billing_email';
                $meta_value   = sanitize_email( $user_var );
            }
            
            $paid_statuses    = array_map( 'esc_sql', wc_get_is_paid_statuses() );
            $product_ids      = is_array( $product_ids ) ? implode(',', $product_ids) : $product_ids;
        
            $line_meta_value  = $product_ids !=  ( 0 || '' ) ? 'AND woim.meta_value IN ('.$product_ids.')' : 'AND woim.meta_value != 0';
        
            // Count the number of products
            $count = $wpdb->get_var( "
                SELECT COUNT(p.ID) FROM {$wpdb->prefix}posts AS p
                INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
                INNER JOIN {$wpdb->prefix}woocommerce_order_items AS woi ON p.ID = woi.order_id
                INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
                WHERE p.post_status IN ( 'wc-" . implode( "','wc-", $paid_statuses ) . "' )
                AND pm.meta_key = '$meta_key'
                AND pm.meta_value = '$meta_value'
                AND woim.meta_key IN ( '_product_id', '_variation_id' ) $line_meta_value 
            " );
        
            // Return true if count is higher than 0 (or false)
            return $count > 0 ? true : false;
        }
 
       
       
        /**
         * 
         *  registrace uzivatele v oblasti pokladny 
         * 
         *
         * 
         */
            
       function wc_register_guests( $order_id ) {

            $checkcart = $this->check_cart_before_checkout_for_registration();
            if ($checkcart > 0 && !is_user_logged_in()){
                // get all the order data
                $order = new WC_Order($order_id);
                
                //get the user email from the order
                $order_email = $order->billing_email;
                
                // check if there are any users with the billing email as user or email
                $email = email_exists( $order_email );  
                $user = username_exists( $order_email );
                
                // if the UID is null, then it's a guest checkout
                if( $user == false && $email == false ){
                    if ($_POST['account_username']){
                        $order_email = $_POST['account_username'];
                        $user_email = $order->billing_email;
                    }
                
                // random password with 12 chars
                // $random_password = wp_generate_password();
                $random_password = $_POST['account_password'];
            
                // create new user with email as username & newly created pw
                $user_id = wp_create_user( $order_email, $random_password,( $order_email == $order->billing_email ? $order_email : $user_email) );
                // wp_mail( $order_email, $order_email, 'Username : '.$order_email.'<br>Password : '.$random_password , $headers, $attachments );
                //WC guest customer identification
                update_user_meta( $user_id, 'guest', 'yes' );
            
                //user's billing data
                update_user_meta( $user_id, 'billing_address_1', $order->billing_address_1 );
                update_user_meta( $user_id, 'billing_address_2', $order->billing_address_2 );
                update_user_meta( $user_id, 'billing_city', $order->billing_city );
                update_user_meta( $user_id, 'billing_company', $order->billing_company );
                update_user_meta( $user_id, 'billing_country', $order->billing_country );
                update_user_meta( $user_id, 'billing_email', $order->billing_email );
                update_user_meta( $user_id, 'billing_first_name', $order->billing_first_name );
                update_user_meta( $user_id, 'billing_last_name', $order->billing_last_name );
                update_user_meta( $user_id, 'billing_phone', $order->billing_phone );
                update_user_meta( $user_id, 'billing_postcode', $order->billing_postcode );
                update_user_meta( $user_id, 'billing_state', $order->billing_state );
            
                // user's shipping data
                update_user_meta( $user_id, 'shipping_address_1', $order->shipping_address_1 );
                update_user_meta( $user_id, 'shipping_address_2', $order->shipping_address_2 );
                update_user_meta( $user_id, 'shipping_city', $order->shipping_city );
                update_user_meta( $user_id, 'shipping_company', $order->shipping_company );
                update_user_meta( $user_id, 'shipping_country', $order->shipping_country );
                update_user_meta( $user_id, 'shipping_first_name', $order->shipping_first_name );
                update_user_meta( $user_id, 'shipping_last_name', $order->shipping_last_name );
                update_user_meta( $user_id, 'shipping_method', $order->shipping_method );
                update_user_meta( $user_id, 'shipping_postcode', $order->shipping_postcode );
                update_user_meta( $user_id, 'shipping_state', $order->shipping_state );
                
                // link past orders to this newly created customer
                wc_update_new_customer_past_orders( $user_id );
                
                }
            }
       
       }
         /**
         * 
         *  zprava pokud naštevnik neni prihlašený 
         * 
         *
         * 
         */

        function mess_if_in_cart(){

            $items_count = 0;
            $found = 0;
            $items = WC()->cart->get_cart();
            if( $items ){
                foreach($items as $item => $values) {
                    $product = wc_get_product( $values['product_id'] );
                    if( $product->get_type() == 'restricted_video' ){
                        
                        if(!is_user_logged_in()) {
                        // echo '<div class="woocommerce-info">'.__('Při obednávce se vám vytvoří učet a přihlašovací udaje přijdou na e-mail', 'wcpt' ).'</div>';
                            wc_add_notice( __( 'Při obednávce jednoho z produktu je protřeba se registrovat nebo prihlasit', 'wcpt' ), 'notice' );
                        }
                    }
                
                }
            }
        
        }

         /**
         * 
         *  oveření či má zakaznik v košiku video produkt
         * 
         *
         * 
         */
        function check_cart_before_checkout_for_registration(){

            $count = 0;
            $items = WC()->cart->get_cart();
                if( $items ){
                    foreach($items as $item => $values) {
                        $product = wc_get_product( $values['product_id'] );
                        if( $product->get_type() == 'restricted_video' ){
                        $count++;
                        
                        }
                    
                    }
                }
                  return $count;
         }



  

        /**
         * 
         *  přidaní fields pro username a heslo 
         * 
         *
         * 
         */


        function confirm_password_checkout( $checkout ) {
            $checkcart = $this->check_cart_before_checkout_for_registration();
            if ( get_option( 'woocommerce_registration_generate_password' ) == 'no' ) {
                    
                    if ($checkcart > 0 && !is_user_logged_in()){
                        
                        woocommerce_form_field(
                            'account_username',
                                array(
                                    'type'              => 'text',
                                    'label'             => __( 'username', 'woocommerce' ),
                                    'required'          => false,
                                    'placeholder'       => _x( 'Pokud zustane prázdné, použije se e-mailová adresa.', 'placeholder', 'woocommerce' )
                                ),
                                $checkout->get_value( 'account_username' )
                                );
                    woocommerce_form_field(
                        'account_password',
                            array(
                                'type'              => 'password',
                                'label'             => __( 'Password', 'woocommerce' ),
                                'required'          => true,
                                'placeholder'       => _x( 'Password', 'placeholder', 'woocommerce' )
                            ),
                            $checkout->get_value( 'account_password' )
                            );
                    woocommerce_form_field(
                    'account_password_2',
                        array(
                            'type'              => 'password',
                            'label'             => __( 'Password confirmation', 'woocommerce' ),
                            'required'          => true,
                            'placeholder'       => _x( 'Password repeat', 'placeholder', 'woocommerce' )
                        ),
                        $checkout->get_value( 'account_password_2' )
                        );
                    }
            }
        }
            /**
         * 
         *  ulozeni hesla a username do post
         * 
         *
         * 
         */

        function save_password_confirm( $order_id ) {
            if ( ! empty( $_POST['account_password']) && ! empty( $_POST['account_password_2'])) {
                    update_post_meta( $order_id, 'account_password', sanitize_text_field( $_POST['account_password'] ) );
                    update_post_meta( $order_id, 'account_password_2', sanitize_text_field( $_POST['account_password_2'] ) );
                    if (! empty( $_POST['account_username'])){
                    update_post_meta( $order_id, 'account_username', sanitize_text_field( $_POST['account_username'] ) );
                        }
                }
        }

            /**
         * 
         *  overeni shody hesla
         * 
         *
         * 
         */
        function confirm_password_validation( $posted ) {
            $checkcart = $this->check_cart_before_checkout_for_registration();
            if ($checkcart > 0 && !is_user_logged_in()){
                if ( ! is_user_logged_in()) {
                    if (!$_POST['account_password'] != $_POST['account_password_2'] ) {
                        wc_add_notice( __( 'Hesla se neshodují', 'woocommerce' ), 'error' );
                    }
                }
            }
        }
            /**
         * 
         *  povinost vyplnení hesla
         * 
         *
         * 
         */

            
        
        function checkout_field_password() {
            // Check if set, if its not set add an error.
            if ( ! $_POST['account_password'] )
                wc_add_notice( __( 'Můsíte vyplnit heslo' ), 'error' );
        }       
            
        



        
    }

    new videoRestrictedProduct;
}
