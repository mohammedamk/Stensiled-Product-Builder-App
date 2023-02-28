<?php
class Auth extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Global_model');
    $this->load->library('form_validation');
  }

  //App Install View Page
  public function Install_login() {
    $this->load->view('auth/appinstall');
  }

  //Check For Login
  public function check_login() {
    $shop = $this->input->get('shop');
    if ($shop != '') {
      if ($this->db->table_exists('shopify_stores') === TRUE) {
        $this->auth($shop);
      } else {
        $this->CreateTable($shop);
      }
    } else {
      echo 'Unauthorized Access!';
			exit;
      // redirect('Auth/Install_login');
    }
  }

  public function CreateTable($shop='')
  {
    $query = "CREATE TABLE `shopify_stores` (
      `id` int(11) NOT NULL,
      `token` varchar(100) DEFAULT NULL,
      `shop` varchar(100) DEFAULT NULL,
      `store_id` int(11) DEFAULT NULL,
      `domain` varchar(100) DEFAULT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `active` tinyint(1) NOT NULL DEFAULT '1',
      `code` text,
      `hmac` text,
      `charge_id` varchar(100) DEFAULT NULL
    )";
    $this->db->query($query);
    $this->auth($shop);
  }

  //Authenticate Shopify Store
  public function auth($shop) {
    $data = array(
      'API_KEY' => $this->config->item('shopify_api_key'),
      'API_SECRET' => $this->config->item('shopify_secret'),
      'SHOP_DOMAIN' => $shop,
      'ACCESS_TOKEN' => '',
    );

    $this->load->library('Shopify', $data); //load shopify library and pass values in constructor

    $scopes = array('read_products', 'write_products','read_themes', 'write_themes','read_script_tags', 'write_script_tags');

    $redirect_url = $this->config->item('redirect_url');
    $paramsforInstallURL = array(
      'scopes' => $scopes,
      'redirect' => $redirect_url,
    );

    $permission_url = $this->shopify->installURL($paramsforInstallURL);
    $this->load->view('auth/escapeIframe', ['installUrl' => $permission_url]);
  }

  // After Installation callback function
  public function auth_callBack() {
    $code = $this->input->get('code');
    $shop = $this->input->get('shop');

    if (isset($code)) {
      $data = array(
        'API_KEY' => $this->config->item('shopify_api_key'),
        'API_SECRET' => $this->config->item('shopify_secret'),
        'SHOP_DOMAIN' => $shop,
        'ACCESS_TOKEN' => '',
      );
      $this->load->library('Shopify', $data); //load shopify library and pass values in constructor
    }

    $accessToken = $this->shopify->getAccessToken($code);
    $this->updateAccess_Token($accessToken);
    if ($accessToken != '') {
      //$this->charge_exist($shop);
      redirect('Auth/home?shop=' . $shop);
    } else {
      redirect('Auth/Install_login');
    }
  }

  public function charge_exist($shop = '') {
		if (!empty($shop)) {
			$shop_details = $this->Global_model->get_shop_details($shop);
			if ($shop_details) {
				if (empty($shop_details->charge_id)) {
					redirect('Auth/CreateCharge?shop=' . $shop);
				} else {
					redirect('Auth/GetCharge?shop=' . $shop . '&charge_id=' . $shop_details->charge_id);
				}
			} else {
				redirect('Auth/Install_login');
			}
		} else {
			redirect('Auth/Install_login');
		}
	}

  public function CreateCharge() {
    if (isset($_GET['shop']) && !empty($_GET['shop'])) {
      $shop = $_GET['shop'];
      if (!isset($_GET['price'])) {
        $price = 0.00;
        $plan = 0;
        $name = "Free Plan";
      }else{
        if ($_GET['price'] == 9.99) {
          $price = 9.99;
          $plan = 1;
          $name = "Basic Plan";
        }
        if ($_GET['price'] == 24.99) {
          $price = 24.99;
          $plan = 2;
          $name = "Basic Plus Plan";
        }
        if ($_GET['price'] == 44.99) {
          $price = 44.99;
          $plan = 3;
          $name = "Platinum Plan";
        }
      }

      $shopAccess = getShop_accessToken_byShop($shop);
      $this->load->library('Shopify', $shopAccess);

      $data = array(
        "recurring_application_charge" => array(
          "name" => $name,
          "price" => $price,
          "return_url" => base_url('Auth/Charge_return_url?shop=' . $shop.'&plan_id='.$plan),
        ),
      );
      $year = getYear();
      $charge = $this->shopify->call(['METHOD' => 'POST', 'URL' => '/admin/api/'.$year.'/recurring_application_charges.json', 'DATA' => $data], true);

      if ($charge->recurring_application_charge) {
        $charge = $charge->recurring_application_charge;
        // echo "<pre>";
        // print_r($charge);
        // // $che['installUrl'] = $charge;
        // // prin
        $this->load->view('auth/escapeIframe', ['installUrl'=>$charge->confirmation_url]);
        // redirect($charge->confirmation_url);
      } else {
        redirect('Auth/Install_login');
      }
    } else {
      redirect('Auth/Install_login');
    }
  }

  public function CancelCharge(){
    $shop = $_GET['shop'];
    $shop_details = $this->Global_model->get_shop_details($shop);
    $shopAccess = getShop_accessToken_byShop($shop);
    $this->load->library('Shopify', $shopAccess);

    $year = getYear();
    $cancel_charge_res = $this->shopify->call(['DELETE' => 'POST', 'URL' => '/admin/api/'.$year.'/recurring_application_charges/'.$shop_details->charge_id.'.json'], true);
    if ($cancel_charge_res) {
      $where = array('shop' => $shop);
      $data1 = array('charge_id' => '',"plan_id"=>0);
      $update = $this->Global_model->UpdateShopDetails($where,$data1);
      redirect('Auth/AppLoader?shop=' . $shop);
    }
  }

  public function GetCharge() {
    $shop = $_GET['shop'];
    $charge_id = $_GET['charge_id'];

    if (!empty($shop)) {
      $shopAccess = getShop_accessToken_byShop($shop);
      $this->load->library('Shopify', $shopAccess);
      $year = getYear();
      $charge = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/'.$year.'/recurring_application_charges/' . $charge_id . '.json'], true);
      if ($charge) {
        $charge = $charge->recurring_application_charge;
        if ($charge->status != 'active') {
          redirect('Auth/CreateCharge?shop=' . $shop);
        } else {
          redirect('Auth/AppLoader?shop=' . $shop);
        }
      }
    }
  }

  public function Charge_return_url() {

    $shop = $_GET['shop'];
    $charge_id = $_GET['charge_id'];
    $plan_id = $_GET['plan_id'];
    if (!empty($shop)) {

      $shopAccess = getShop_accessToken_byShop($shop);
      $this->load->library('Shopify', $shopAccess);

      $data = array(
        "recurring_application_charge" => array(
          "id" => $charge_id,
          "status" => "accepted"
        ),
      );
      $year = getYear();
      $charge = $this->shopify->call(['METHOD' => 'POST', 'URL' => '/admin/api/'.$year.'/recurring_application_charges/' . $charge_id . '/activate.json', 'DATA' => $data], true);
      if ($charge) {
        $where = array('shop' => $shop);
        $data1 = array('charge_id' => $charge_id,"plan_id"=>$plan_id);
        $update = $this->Global_model->UpdateShopDetails($where,$data1);
        if ($update) {
          redirect('Auth/AppLoader?shop=' . $shop);
        } else {
          $charge = $charge->recurring_application_charge;
          $data['installUrl'] = $charge->confirmation_url;
          $this->load->view('auth/escapeIframe', $data);
        }
      } else {
        redirect('Auth/Install_login');
      }
    }
  }

  //Home Page Controller
  public function home() {
    $code = $this->input->get('code');
    $shop = $this->input->get('shop');
    if (empty($shop)) {
      echo 'Unauthorized Access!';
      exit;
    }
    $this->AppLoader($shop);
    // $this->charge_exist($shop);
  }

  public function AppLoader($shop='') {
    $shop = $this->input->get('shop');
    if (empty($shop)) {
      echo 'Unauthorized Access!';
      exit;
    }
    if (isset($shop)) {
      if ($shop != '') {
        $accessData = getShop_accessToken_byShop($shop);
        if (count($accessData) > 0) {
          if ($accessData['ACCESS_TOKEN'] != '') {
            $data['access_token'] = $accessData['ACCESS_TOKEN'];
            $this->addButton();
            // $this->load->view('welcome', $data);
            redirect('Home/Dashboard?shop='.$shop);
          } else {
            redirect('Auth/Install_login');
          }
        } else {
          redirect('Auth/Install_login');
        }
      }
    } else {
      redirect('Auth/Install_login');
    }
  }

  //DashBoard
  public function dashabord($data) {
    $this->load->load_admin('app/dashboard', $data);
  }

  public function updateAccess_Token($accessToken) {
    if ($_GET['shop'] != '' && $_GET['code'] != '' && $_GET['hmac'] != '') {
      $shopdata = array('shop' => $_GET['shop'], 'code' => $_GET['code'], 'hmac' => $_GET['hmac']);
      $check_shop_exist = $this->Global_model->check_ShopExist($_GET['shop']);
      if ($check_shop_exist) {
        $this->Global_model->update_Shop($shopdata, $accessToken);
      } else {
        $this->Global_model->add_newShop($shopdata, $accessToken);
      }
    }
  }

  public function addButton() {
    $shop       = $_GET['shop'];

    $shopAccess = getShop_accessToken_byShop($shop);

    $this->load->library('Shopify', $shopAccess);

    // Run themes api in order to get all the themes info installed in the store
    $themes = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/'.getYear().'/themes.json']);

    $themes_array = $themes->themes;

    // Get published theme id
    $theme_id;
    foreach ($themes_array as $theme) {
      if ($theme->role == "main") {
        $theme_id = $theme->id;
      }
    }

    // Get contents of product.liquid
    $product_template_contents = $this->shopify->call(['METHOD' => 'GET', 'URL' => '/admin/api/'.getYear().'/themes/'.$theme_id.'/assets.json?asset[key]=templates/product.liquid'], true);
    $product_template_contents = $product_template_contents->asset->value;

    // JS script to add the button
    $custom_js_script = "<script>(function() {var form_tags = document.querySelectorAll('form[action]');form_tags.forEach(function(form_tag){if(form_tag.action.indexOf('/cart/add') !== -1){var newText = document.createElement( 'a' );newText.className = 'btn';newText.href = href='/apps/custmiz?pid={{product.id}}';var text = document.createTextNode('Customize this Product');newText.appendChild(text);var product_collections = {{ product.collections | json }};product_collections.forEach(function (collection) {if(collection.handle == 'stensiledv2') {form_tag.parentNode.insertBefore( newText, form_tag.nextSibling );return false;}});}});})();</script>";

    $params = array(
      'asset' => array(
        "key" => "templates/product.stensiledv2.liquid",
        "source_key" => "templates/product.liquid"
      )
    );
    // Run backup api
    $product_backup = $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/'.getYear().'/themes/'.$theme_id.'/assets.json', 'DATA' => $params], true);
      // concatinate product.liquid and js
      $new_product_template_contens = $product_template_contents . PHP_EOL  . $custom_js_script;

      $params = array(
        'asset' => array(
          "key" => "templates/product.stensiledv2.liquid",
          "value" => $new_product_template_contens
        )
      );

      // Finally create a button
      $product_template = $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/'.getYear().'/themes/'.$theme_id.'/assets.json', 'DATA' => $params], true);
    }
}
