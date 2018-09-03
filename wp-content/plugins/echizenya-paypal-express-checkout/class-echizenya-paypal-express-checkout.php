<?php
class Echizenya_PayPal_Express_Checkout {

  // プロパティ
  private $options;

  // コンストラクタ
  public function __construct() {
    add_action( 'admin_menu', array($this, 'paypalexpresscheckout_add_admin_menu') );
  }
}
