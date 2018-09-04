<?php
class Echizenya_PayPal_Express_Checkout {

  // プロパティ
  private $options;

  // コンストラクタ
  public function __construct() {
    add_action( 'admin_menu', array($this, 'paypalexpresscheckout_add_admin_menu') );
    // 追記
    add_action( 'admin_init', array( $this, 'paypal_init' ) );
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

  // 設定画面を表示するメソッド
  public function create_admin_page() {
    $this->options = get_option( 'echizenya_paypal_express_checkout' );
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

  public function paypal_init() {

    register_setting(
      'paypal-settings-group',
      'echizenya_paypal_express_checkout',
      array( $this, 'sanitize' )
    );

    add_settings_section(
      'setting_section_id',
      'PayPal ExpressCheckout Settings',
      array( $this, 'print_section_info' ),
      'paypal-settings-group'
    );

    add_settings_field(
      'env',
      'Enviroment',
      array( $this, 'enviroment_callback' ),
      'paypal-settings-group',
      'setting_section_id'
    );

    add_settings_field(
      'client',
      'cleint ID',
      array( $this, 'client_callback' ),
      'paypal-settings-group',
      'setting_section_id'
    );

  }

  // 実行環境とclient IDをサニタイズしてユーザーの入力値を配列で返す
  public function sanitize( $input ) {
    $new_input = array();
    if( isset( $input['env'] ) ) {
      $new_input['env'] = sanitize_text_field( $input['env'] );
    }
    if( isset( $input['client'] ) ) {
      $new_input['client'] = sanitize_text_field( $input['client'] );
    }
    return $new_input;
  }

  // 設定ページのヘッダー文字列を表示
  public function print_section_info() {
    print 'Enter your settings below:';
  }

  // 実行環境の選択
  public function enviroment_callback() {
    ?>
      <p>
        <select name="echizenya_paypal_express_checkout[env]" size="1">
          <option value="sandbox">sandbox</option>
          <option value="production">production</option>
        </select>
      </p>
    <?php
  }

  // client IDの入力
  public function client_callback() {
    printf(
            '<input type="text" id="title" name="echizenya_paypal_express_checkout[client]" size="90" value="%s" />',
            isset( $this->options['client'] ) ? esc_attr( $this->options['client']) : ''
          );
  }

}
