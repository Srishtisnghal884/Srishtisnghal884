<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';
add_filter( 'woocommerce_store_api_disable_nonce_check', '__return_true' );
/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */
// remove update notice for NP plugins
// require_once 'inc/rollinglawn-delivery-dates.php';
add_action( 'rest_api_init', function () {
    register_rest_route( 'v1', '/rolllawn/zip_code=(?P<plz>\d+)&price=(?P<qm>\d+)', array(
        'methods' => 'GET',
        'callback' => 'my_awesome_func',
    ) );
} );
function my_awesome_func( $data ) {
     $zip = $data['plz'];
     $sqm = $data['qm'];
     $selected_date = false;
     if ( WC()->session && ! empty( WC()->session->get( 'rr_rolling_lawns_delivery_date' ) ) ) {
       $selected_date = new DateTime( WC()->session->get( 'rr_rolling_lawns_delivery_date' ) );
    }
     if ( $zip && $sqm ) {
         $delivery_dates = ( new RR_Cart_Delivery_Dates( $zip, $sqm, empty( $selected_date ) ) )->get_delivery_dates();
            if ( ! empty( $delivery_dates[0] ) ) {
                $fake_date = clone( $delivery_dates[0] );
                $fake_date->get_date()->modify( '-1 day' );
                array_unshift( $delivery_dates, $fake_date );
            }
            $date_data = array();
            foreach ($delivery_dates as $key => $value) {
                 $date_data[] = $value->get_date();
            }
          return $date_data;   
     }
  //   if ( $zip && $sqm ) {
  //     $dates = flatsome_child_get_possible_delivery_dates( $zip, 5, $sqm );
  //      array_unshift($dates, date_modify(clone($dates[0]), '-1 day'));
  //     return $dates;    
  // }
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'v1', '/rolllawn-price/zip_code=(?P<plz>\d+)&price=(?P<qm>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_price_per_sq_meters',
    ) );
} );
function get_price_per_sq_meters( $data ) {
     $zip = $data['plz'];
     $sqm = $data['qm'];
    if ( $zip && $sqm ) {
        $prekuehl = false;
        $fcLawnState = flatsome_child_validate_price_form_params( $sqm, $zip );   
      //product_ids
        $spielrasen_id    = '244738';

        //get product price
         $spielrasen_product = wc_get_product( $spielrasen_id );

      if ( $fcLawnState == 'valid' ) {
          $pric = flatsome_child_calc_rolling_lawn_price( $spielrasen_id, $zip, $sqm, $prekuehl );
          $pr = intval($pric);
          // $originalPrices = array(
          //   ceil( $pr + ( $sqm * 0.40 ) )
          // );
          if ( ! $prices ) {
            $fcLawnState = "priceCalcFailed";
          }
        }
        //  $pr = number_format( $prices[0], 0, ',', '.' ).'€';
        
         $par_sq_m = $pr.' € <span>inkl. MwSt. | '.number_format( ( $pr / $sqm ), 2, ',', '.' ).'€/m<sup>2</sup></span>';
         $price_data = array('price' => $pr, 'par_sq_m'=> $par_sq_m);
         return $price_data ;
  }
}

add_action('rest_api_init', function () {
    register_rest_route('v1/', 'wc-nonce', array(
        'methods'  => 'GET',
        'callback' => 'generate_wc_nonce'
    ));
});

function generate_wc_nonce($request)
{

    $response = new WP_REST_Response(wp_create_nonce('wc_store_api'));
    $response->set_status(200);

    return $response;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'v1', '/rolllawn-sell-product/zip_code=(?P<plz>\d+)&price=(?P<qm>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_upsell_product',
    ) );
} );
function get_upsell_product( $data ) {
    $zip = $data['plz'];
    $sqm = $data['qm'];

    // global $woocommerce;
    if ( $zip && $sqm ) {
            // include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
            // include_once WC_ABSPATH . 'includes/class-wc-cart.php';

            if ( is_null( WC()->cart ) ) {
                wc_load_cart();
            }
         $prekuehlPrice = ceil( $sqm * FLC_PREKUEHL_PRICE_PER_SQM );

         $cell_product = array();
       
            $pre_price = str_replace( ',00', '', $prekuehlPrice ).' € inkl. MwSt.';
            $img_p = site_url().'/wp-content/themes/flatsome-child/assets/img/prekuehl.jpg';
            $prekuehl_product  = array('title' => 'Prekühlverfahren - Kühlung vor Lieferung','description' => 'Der Rollrasen wird vor Lieferung auf 3-5 Grad heruntergekühlt. Dadurch bleibt er während des Transports länger frisch und wächst besser an.', 'price'=>$pre_price,'product_id'=> '','display_price'=>$prekuehlPrice);
           $cell_product[] = $prekuehl_product;
        

            $bodenaktivatorProduct    = wc_get_product( FLC_BODENAKTIVATOR_ID );
            $bodenaktivatorVariations = $bodenaktivatorProduct->get_available_variations();
            //calc right variation of Dünger
            $bodenaktivatorSelectedVariationId = 0;
            $bodenaktivatorSelectedVariation   = null;
            foreach ( $bodenaktivatorVariations as $v ) {
                if ( floatval( str_replace( ',', '.', $v['attributes']['attribute_menge'] ) ) >= $sqm * ( 135 / 1000 ) ) {
                    $bodenaktivatorSelectedVariationId = $v['variation_id'];
                    $bodenaktivatorSelectedVariation   = $v;
                    break;
                }
            }

             // echo"<pre>";print_r($bodenaktivatorVariations);
           $vari_val = 'Empfohlene Menge für '.$sqm.'qm Rollrasen: '.$bodenaktivatorSelectedVariation['attributes']['attribute_menge'].' Bodenaktivator';

            $BODE_price = str_replace( ',00', '', $bodenaktivatorSelectedVariation['display_price'] ).'€
                        inkl. MwSt.& Versand ('.$bodenaktivatorSelectedVariation['unit_price'].')';

            $img_body = $bodenaktivatorVariations[0]['image']['url'] ;
            $BODE_product  = array('title' => 'Rollrasen Rudi Bio-Bodenaktivator','description' => 'Versorgt den Boden mit den Nährstoffen, die der Rasen braucht.', 'price'=>$BODE_price, 'product_id'=>FLC_BODENAKTIVATOR_ID, 'varient_id'=>$bodenaktivatorSelectedVariation['variation_id'], 'variation'=>$vari_val,'display_price'=>$bodenaktivatorSelectedVariation['display_price'] );
           $cell_product[] = $BODE_product;
        

           //get product "Starterdünger"
            $starterduengerProduct    = wc_get_product( FLC_STARTERDUENGER_ID );
            $starterduengerVariations = $starterduengerProduct->get_available_variations();
            //calc right variation of Starterdünger
            $starterduengerSelectedVariationId = 0;
            $starterduengerSelectedVariation   = null;
            foreach ( $starterduengerVariations as $v ) {
                if ( floatval( str_replace( ',', '.', $v['attributes']['attribute_menge'] ) ) >= $sqm * ( 40 / 1000 ) ) {
                    $starterduengerSelectedVariationId = $v['variation_id'];
                    $starterduengerSelectedVariation   = $v;
                    break;
                }
            }

           $vari_vals = 'Empfohlene Menge für '.$sqm.'qm Rollrasen: '.$starterduengerSelectedVariation['attributes']['attribute_menge'].' Bodenaktivator';

            $start_price = str_replace( ',00', '', $starterduengerSelectedVariation['display_price'] ).' €
                        inkl. MwSt.& Versand ('.$starterduengerSelectedVariation['unit_price'].')';

            $img_start = $starterduengerVariations[0]['image']['url'];
            $start_product  = array('title' => 'Rollrasen Rudi Bio-Starterdünger','description' => 'Unterstützt die Wurzelbildung. Wichtig für das Anwachsen des Rasens.', 'price'=>$start_price, 'product_id'=>FLC_STARTERDUENGER_ID,'varient_id'=>$starterduengerSelectedVariation['variation_id'],'variation'=>$vari_vals,'display_price'=>$starterduengerSelectedVariation['display_price'] );
           $cell_product[] = $start_product;
       

           //get product "FRÜHJAHRSDÜNGER"
            $fruehjahrsduengerProduct    = wc_get_product( FLC_FRUEHJAHRSDUENGER_ID );
            $fruehjahrsduengerVariations = $fruehjahrsduengerProduct->get_available_variations();
            //calc right variation of FRÜHJAHRSDÜNGER
            $fruehjahrsduengerSelectedVariationId = 0;
            $fruehjahrsduengerSelectedVariation   = null;
            foreach ( $fruehjahrsduengerVariations as $v ) {
                if ( floatval( str_replace( ',', '.', $v['attributes']['attribute_menge'] ) ) >= $sqm * ( 50 / 1000 ) ) {
                    $fruehjahrsduengerSelectedVariationId = $v['variation_id'];
                    $fruehjahrsduengerSelectedVariation   = $v;
                    break;
                }
            }
            $string = 'Empfohlene Menge für '.$sqm.'qm Rollrasen: '.$fruehjahrsduengerSelectedVariation['attributes']['attribute_menge'].' Bodenaktivator';

            $start_price = str_replace( ',00', '', $fruehjahrsduengerSelectedVariation['display_price'] ).' €
                        inkl. MwSt.& Versand ('.$fruehjahrsduengerSelectedVariation['unit_price'].')';
            $img_start = $fruehjahrsduengerVariations[0]['image']['url'];
            $start_product  = array('title' => 'Rollrasen Rudi Frühjahrsdünger','description' => 'Langzeitdünger mit dem perfekten Nährstoffmix für den Frühling.', 'price'=>$start_price, 'product_id'=>FLC_FRUEHJAHRSDUENGER_ID,'varient_id'=>$fruehjahrsduengerSelectedVariation['variation_id'], 'variation'=>$string ,'display_price'=>$fruehjahrsduengerSelectedVariation['display_price'] );
           $cell_product[] = $start_product;
       
      // echo"<pre>";print_r($cell_product);
       return $cell_product;
    }
}
 add_action('init', 'handle_preflight');
function handle_preflight() {
    $origin = get_http_origin();
    if ($origin === 'https://ba8yy29.myraidbox.de') {
        header("Access-Control-Allow-Origin: https://ba8yy29.myraidbox.de");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
        if ('OPTIONS' == $_SERVER['REQUEST_METHOD']) {
            status_header(200);
            exit();
        }
    }
}
add_filter('rest_authentication_errors', 'rest_filter_incoming_connections');
function rest_filter_incoming_connections($errors) {
    $request_server = $_SERVER['REMOTE_ADDR'];
    $origin = get_http_origin();
    if ($origin !== 'https://ba8yy29.myraidbox.de') return new WP_Error('forbidden_access', $origin, array(
        'status' => 403
    ));
    return $errors;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'v1', '/rolllawn_get_product_price/product_id=(?P<product_id>\d+)&price=(?P<qm>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_product_price',
    ) );
});

function get_product_price( $data ) {
    $product_id = $data['product_id'];
    $sqm = $data['qm'];
    // global $woocommerce;
    if ( $product_id != '' && $sqm != '' ) {

        if($product_id == FLC_BODENAKTIVATOR_ID)
        {
          $vari_sqm = $sqm * ( 135 / 1000 );
          $product_ids = FLC_BODENAKTIVATOR_ID;
        }         
        if($product_id == FLC_STARTERDUENGER_ID)
        {
          $vari_sqm = $sqm * ( 40 / 1000 );
          $product_ids = FLC_STARTERDUENGER_ID;
        }         
        if($product_id == FLC_FRUEHJAHRSDUENGER_ID)
        {
          $vari_sqm = $sqm * ( 50 / 1000 );
          $product_ids = FLC_FRUEHJAHRSDUENGER_ID;
        }

            $cell_product = array();

            $bodenaktivatorProduct    = wc_get_product(  $product_id );
            $bodenaktivatorVariations = $bodenaktivatorProduct->get_available_variations();
            //calc right variation of Dünger
            $bodenaktivatorSelectedVariationId = 0;
            $bodenaktivatorSelectedVariation   = null;
            foreach ( $bodenaktivatorVariations as $v ) {
                if ( floatval( str_replace( ',', '.', $v['attributes']['attribute_menge'] ) ) >= $vari_sqm ) {
                    $bodenaktivatorSelectedVariationId = $v['variation_id'];
                    $bodenaktivatorSelectedVariation   = $v;
                    break;
                }
            }


            $start_product  = array('price'=>$bodenaktivatorSelectedVariation['display_price'], 'product_id'=>$product_ids,'varient_id'=>$bodenaktivatorSelectedVariation['variation_id']);
            $cell_product[] = $start_product;
            return $cell_product;
    }
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'v1', '/rolllawn_add_product_cart/product_id=(?P<product_id>\d+)&price=(?P<price>\d+)', array(
        'methods' => 'GET',
        'callback' => 'add_product_in_cart',
    ) );
});

function add_product_in_cart( $data ) {
    $product_id = $data['product_id'];
    $price = $data['price'];
    $variation_id = '';
     if ( $product_id != '' && $price != '' ) {

            $quantity     = 1;
            $variation_id = $variation_id ? $variation_id : '';
             if ( is_null( WC()->cart ) ) {
                wc_load_cart();
            }
            try {
                if ( ! WC()->cart->is_empty() && in_array( $product_id,  array( 244738 ) ) ) {
                    foreach ( WC()->cart->get_cart() as $key => $item ) {
                        if ( $item['product_id'] == $product_id ) {
                            WC()->cart->empty_cart();
                            break;
                        }
                    }
                }
            } catch ( Exception $e ) {
                return( $e->getMessage() );
            }

            try {
                
                $cart_item = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
                if ( $cart_item === false ) {
                    return( __( 'Product don\'t exist.', FLC_TEXT_DOMAIN ) );
                }
            } catch ( Exception $e ) {
                return( $e->getMessage() );
            }
            // print_r(WC()->cart);
            $itemsInCart  = WC()->cart->get_cart_contents_count();
            $amountInCart = number_format( WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax(), 2, ',', '.' );
            $cartdata = array( 'itemsInCart' => $itemsInCart, 'amountInCart' => $amountInCart );
            return  $cartdata;
             // print_r($cartdata);
     }

}