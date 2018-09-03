<?php
class Echizenya_PayPal_Express_Checkout {

  // プロパティ
  private $options;

  // コンストラクタ
  public function __construct() {
    add_action( 'admin_menu', array($this, 'paypalexpresscheckout_add_admin_menu') );
  }

  // 管理画面にサブメニューを表示するメソッド
  public function paypalexpresscheckout_add_admin_menu() {
      add_options_page(
        'Settings Admin',
        'Echizenya PayPal ExpressCheckout',
        'manage_options',
        'paypal-settings-group',
        array( $this, 'create_admin_page' )
    );
  }

  // 管理画面を表示するメソッド
  public function create_admin_page() {
    $this->options = get_option( 'paypal_option_name' );
    ?>
    <div class="wrap">
       <h2>Echizenya PayPal ExpressCheckout Settings</h2>
       <form method="post" action="options.php">
         <?php settings_fields( 'paypal-settings-group' ); ?>
         <?php do_settings_sections( 'paypal-settings-group' ); ?>
         <?php submit_button(); ?>
       </form>
    </div>
    <?php
  }


}
