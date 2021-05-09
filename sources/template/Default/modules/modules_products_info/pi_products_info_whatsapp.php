<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class pi_products_info_whatsapp {
    public string $code;
    public string $group;
    public string $title;
    public string $description;
    public ?int $sort_order = 0;
    public bool $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('module_products_info_whatsapp');
      $this->description = CLICSHOPPING::getDef('module_products_info_whatsapp_description');

      if (\defined('MODULE_PRODUCTS_INFO_WHATSAPP_STATUS')) {
        $this->sort_order = MODULE_PRODUCTS_INFO_WHATSAPP_SORT_ORDER;
        $this->enabled = (MODULE_PRODUCTS_INFO_WHATSAPP_STATUS == 'True');
      }
    }

    public function execute() {
      $CLICSHOPPING_ProductsCommon = Registry::get('ProductsCommon');

      if ($CLICSHOPPING_ProductsCommon->getID() && isset($_GET['Products'])) {
        $content_width = (int)MODULE_PRODUCTS_INFO_WHATSAPP_CONTENT_WIDTH;
        $text_position = MODULE_PRODUCTS_INFO_WHATSAPP_POSITION;

        $CLICSHOPPING_Template = Registry::get('Template');

        $products_id = $CLICSHOPPING_ProductsCommon->getID();

        $product_name = HTML::sanitize($CLICSHOPPING_ProductsCommon->getProductsName($products_id));
        $phone_number = HTML::sanitize(MODULE_PRODUCTS_INFO_WHATSAPP_PHONE_NUMBER);
        $products_model =  $CLICSHOPPING_ProductsCommon->getProductsModel();
        $products_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if (!empty($phone_number)) {
          $products_whatsapp_content = '<!-- Start $products_whatsapp -->' . "\n";

          ob_start();
          require_once($CLICSHOPPING_Template->getTemplateModules($this->group . '/content/products_info_whatsapp'));
          $products_whatsapp_content .= ob_get_clean();

          $products_whatsapp_content .= '<!-- $products_whatsapp -->' . "\n";

          $CLICSHOPPING_Template->addBlock($products_whatsapp_content, $this->group);
        }
      }
    } // public function execute

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return \defined('MODULE_PRODUCTS_INFO_WHATSAPP_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to enable this module ?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_WHATSAPP_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to enable this module ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please indicate you phone number?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_WHATSAPP_PHONE_NUMBER',
          'configuration_value' => '',
          'configuration_description' => 'Insert your phone number',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please select the width of the display?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_WHATSAPP_CONTENT_WIDTH',
          'configuration_value' => '12',
          'configuration_description' => 'Please enter a number between 1 and 12',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => 'clic_cfg_set_content_module_width_pull_down',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'A quel endroit souhaitez-vous afficher le bouton ?',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_WHATSAPP_POSITION',
          'configuration_value' => 'none',
          'configuration_description' => 'Affiche le code barre du produit à gauche ou à droite<br><br><i>(Valeur Left = Gauche <br>Valeur Right = Droite <br>Valeur None = Aucun)</i>',
          'configuration_group_id' => '6',
          'sort_order' => '2',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'float-end\', \'float-start\', \'float-none\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort order',
          'configuration_key' => 'MODULE_PRODUCTS_INFO_WHATSAPP_SORT_ORDER',
          'configuration_value' => '5',
          'configuration_description' => 'Sort order of display. Lowest is displayed first. The sort order must be different on every module',
          'configuration_group_id' => '6',
          'sort_order' => '3',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array(
        'MODULE_PRODUCTS_INFO_WHATSAPP_STATUS',
        'MODULE_PRODUCTS_INFO_WHATSAPP_PHONE_NUMBER',
        'MODULE_PRODUCTS_INFO_WHATSAPP_CONTENT_WIDTH',
        'MODULE_PRODUCTS_INFO_WHATSAPP_POSITION',
        'MODULE_PRODUCTS_INFO_WHATSAPP_SORT_ORDER'
      );
    }
  }
