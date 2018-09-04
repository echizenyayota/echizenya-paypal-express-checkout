<?php
/*
Plugin Name: Echizenya PayPal Express Checkout
Plugin URI: https://example.com
Description: Blog Commerce PayPal Express Checkout
Version: 0.0.0
Author: echizenya
Author URI: https://e-yota.com
License: GPLv2 or later
Text Domain: echizenya-paypal-express-checkout
*/

require(__DIR__ . '/class-echizenya-paypal-express-checkout.php');

if( is_admin() ) {
  $e_pypl_expr = new Echizenya_PayPal_Express_Checkout();
}

// checkout.jsの読み込み
function epec_paypal_scripts() {
  wp_enqueue_script( 'paypal-checkout', 'https://www.paypalobjects.com/api/checkout.js' );
}
add_action( 'wp_enqueue_scripts', 'epec_paypal_scripts' );

// ショートコード用のフック
function epec_paypaldiv_func( $atts ){
  $option = get_option( 'echizenya_paypal_express_checkout' );
  $config = shortcode_atts(
    array(
        'id'        =>  '',                 // ユーザーが任意番号でつけるid
        'total'     =>  '0',                // 決済金額
        'currency'  =>  '',                 //決済通貨(JPY, USD)
        'color'     =>  '',                 // ボタンの色(gold, blue, silver, black)
        'size'      =>  '',                 // ボタンのサイズ(large, medium, small, responsive)
        'env'       =>  $option['env'],     // 実行環境
        'client'    =>  $option['client'],  //  client ID
  	 ),
  	$atts );

  // ショートコードで属性値の上書きができない場合はプログラム終了
  if ( ! $config['id'] ||
       $config['total'] === '0' ||
       ! $config['currency'] ||
       ! $config['env'] ||
       ! $config['client']
  ) return;

  $paypaldiv = '<div id="' . $config['id'] . '"></div>';
  $paypaldiv .= "<script>
    paypal.Button.render({
      env: '$config[env]',
      client: {
        $config[env]: '$config[client]',
      },
      style: {
        color: '$config[color]',
        size: '$config[size]',
      },
      commit: true,
      payment: function(data, actions) {
        return actions.payment.create({
          payment: {
            transactions: [{
              amount: { total: '$config[total]', currency: '$config[currency]' }
            }]
          }
        });
      },
      onAuthorize: function(data, actions) {
        return actions.payment.execute().then(function() {
          window.alert('Payment Complete!');
        })
      }
    }, '#$config[id]');
  </script>";

  return $paypaldiv;

}

add_shortcode( 'paypaldiv', 'epec_paypaldiv_func' );
