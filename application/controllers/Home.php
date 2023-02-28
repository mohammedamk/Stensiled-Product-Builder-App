<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
require_once APPPATH . 'libraries/simple_html_dom.php';
class Home extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Global_model');
        $this->load->library('form_validation');
        // $this->load->library('session');
    }

    public function Dashboard()
    {
        if (!empty($_GET['shop'])) {
            $shop            = $_GET['shop'];
            $data['shop']    = $shop;
            $data['shop_id'] = shop_id($shop);
            $data['table']   = 'products';
            $this->load->load_admin('templates/welcome', $data);
        } else {
            $this->load->load_admin('errors/shop-errors/shop-not-found');
        }
    }

    public function shopPlans()
    {
      $shop            = $_GET['shop'];
      $shop_id = shop_id($shop);
      $data = $this->db->query('select * from shopify_stores where id="'.$shop_id.'"')->result();
      $plans['plans'] = $data[0]->plan_id;
      $this->load->view('plans/myPlan',$plans);
    }

    public function CreateProductView()
    {
        if (!empty($_GET['shop'])) {
            $data['shop'] = $_GET['shop'];
            $this->load->load_admin('templates/create-products', $data);
        } else {
            $this->load->load_admin('errors/shop-errors/shop-not-found');
        }
    }

    public function CreateProduct() {

        if (!empty($_GET['shop'])) {
            $isValid = IsValidRequest();

            if($isValid['code'] == 200){

            $shop       = $_GET['shop'];
            $shop_id    = shop_id($shop);
            $shopAccess = getShop_accessToken_byShop($shop);
            $this->load->library('Shopify', $shopAccess);

            // if ($_POST['planid'] == 0 && $_POST['productCount'] == 1) {
            //     $send_data1['code']     = 201;
            //     $send_data1['errors'][] = ['message' => 'You reached the maximum product limit'];
            //     json_send($send_data1);
            //     exit;
            // }

            if ($this->Global_model->check_product($_POST['product_title'], $shop_id) > 0) {
                $send_data['code']     = 201;
                $send_data['errors'][] = ['message' => 'Product already exist with this title!'];
                json_send($send_data);
                exit;
            }

            $dataInfo = $this->do_upload($_FILES);
            if (isset($dataInfo['errors'])) {
                if (isset($dataInfo['success'])) {
                    foreach ($dataInfo['success'] as $file) {
                        $path = 'assets/images/' . $file['file_name'];
                    }
                }
                $dataInfo['code'] = 201;
                json_send($dataInfo);
                exit;
            }

            $images = $dataInfo['success'];
            $images_ = array();
            foreach ($images as $name => $image){
              if (isset($images[$name]) && !empty($image['file_name'])) {
                $path = base_url('assets/images/' .$image['file_name']);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = base64_encode($data);
                $images_[] = ["attachment"=>$base64,"alt"=>$name];
              }
            }

            $size_array = explode(',', $_POST['size_array']);
            $variants = array();

            foreach ($size_array as $size) {
              array_push($variants,[ "option1" => $size,"price" => $_POST['product_price']]);
            }

            $product_data = [
              "product" => [
                "title"        => $_POST['product_title'],
                "body_html"    => $_POST['description'],
                "product_type" => "StensiledV2",
                "variants"     => $variants,
                "options"      => [["name"=>"Size","values"=>$size_array]],
                "images"       => $images_,
                "template_suffix" => "stensiledv2"
              ],
            ];

            // echo "<pre>";
            // print_r($product_data);
            // exit;
            $product = $this->shopify->call(['METHOD' => 'POST', 'URL' => '/admin/api/'.getYear().'/products.json', 'DATA' => $product_data], true);

            if (!isset($product->product)) {
                json_send(array('code' => 100, 'message' => 'Something went wrong!'));
                exit;
            }

            $product = $product->product;
            $get_product_count = $this->db->query("select product_count from products where shop_id='".$shop_id."' order by id desc limit 1")->result();
            if (count($get_product_count) > 0) {
              $get_product_count1 = $get_product_count[0]->product_count;
              $product_count = $get_product_count1 + 1;
            } else {
              $product_count = 1;
            }

            //For Product Collection
            $check_collection_id = $this->db->query("select collection_id from product_collection where shop_id='".$shop_id."' ")->result();
            if (count($check_collection_id) > 0) {
              $collection_id = $check_collection_id[0]->collection_id;
              $this->product_collection($collection_id,$product->id,$shop);
            } else {
              $create_collection = array("custom_collection" => array("title" => "StensiledV2"),);
              $year = getYear();
              $get_collection = $this->shopify->call(['METHOD' => 'POST', 'URL' => '/admin/api/'.$year.'/custom_collections.json', 'DATA' => $create_collection], true);
              $get_collection_id = $get_collection->custom_collection;
              $insertData = ["collection_id" => $get_collection_id->id,"shop_id"=> $shop_id];
              $this->db->insert("product_collection",$insertData);
              $this->product_collection($get_collection_id->id,$product->id,$shop);
            }

            $dataInsert = [
                'title'         => $product->title,
                'handle'        => $product->handle,
                'product_id'    => $product->id,
                'variant_id'    => $product->variants[0]->id,
                'description'   => $product->body_html,
                'color_ids'     => implode(',', array_unique($_POST['productcolor'])),
                'size_ids'      => implode(',', array_unique(explode(',', $_POST['size_array']))),
                'product_price' => $product->variants[0]->price,
                'shop_id'       => $shop_id,
                'product_count' => $product_count,
            ];

            foreach ($images as $name => $image) {
              if (isset($images[$name]) && !empty($image['file_name'])) {
                $dataInsert[$name] = $image['file_name'];
              }
            }
            $product = $this->Global_model->insert_data('products', $dataInsert);
            if ($product) {
                $send_data = array('code' => 200, 'message' => 'Product Created!');
            } else {
                $send_data = array('code' => 100, 'message' => 'Something went wrong!');
            }
            $send_data['shop']    = $shop;
            $send_data['shop_id'] = $shop_id;
            json_send($send_data);
          }else{
            json_send($isValid);
          }

        } else {
            $this->load->load_admin('errors/shop-errors/shop-not-found');
        }
    }

    private function product_collection($collection_id,$productId,$shop)
    {
      $shopAccess = getShop_accessToken_byShop($shop);
      $this->load->library('Shopify', $shopAccess);
      $product_collection = array(
          "collect" => array("product_id" => $productId,"collection_id" => $collection_id),
      );
      $year = getYear();
      $insertCollection = $this->shopify->call(['METHOD' => 'POST', 'URL' => '/admin/api/'.$year.'/collects.json', 'DATA' => $product_collection], true);
      if ($insertCollection) {
        return true;
      }else {
        // $send_data = array('code' => 100, 'message' => 'Something went wrong!');
        // json_send($send_data);
      }
    }

    public function Cust()
    {

       ob_start();
        ?>
        <link rel="stylesheet"  href="<?=base_url()?>assets/css/customizer.css">
        <link href="https://fonts.googleapis.com/css?family=Abel|Abril+Fatface|Acme|Alegreya|Alegreya+Sans|Anton|Archivo|Archivo+Black|Archivo+Narrow|Arimo|Arvo|Asap|Asap+Condensed|Bitter|Bowlby+One+SC|Bree+Serif|Cabin|Cairo|Catamaran|Crete+Round|Crimson+Text|Cuprum|Dancing+Script|Dosis|Droid+Sans|Droid+Serif|EB+Garamond|Exo|Exo+2|Faustina|Fira+Sans|Fjalla+One|Francois+One|Gloria+Hallelujah|Hind|Inconsolata|Indie+Flower|Josefin+Sans|Julee|Karla|Lato|Libre+Baskerville|Libre+Franklin|Lobster|Lora|Mada|Manuale|Maven+Pro|Merriweather|Merriweather+Sans|Montserrat|Montserrat+Subrayada|Mukta+Vaani|Muli|Noto+Sans|Noto+Serif|Nunito|Open+Sans|Open+Sans+Condensed:300|Oswald|Oxygen|PT+Sans|PT+Sans+Caption|PT+Sans+Narrow|PT+Serif|Pacifico|Passion+One|Pathway+Gothic+One|Play|Playfair+Display|Poppins|Questrial|Quicksand|Raleway|Roboto|Roboto+Condensed|Roboto+Mono|Roboto+Slab|Ropa+Sans|Rubik|Saira|Saira+Condensed|Saira+Extra+Condensed|Saira+Semi+Condensed|Sedgwick+Ave|Sedgwick+Ave+Display|Shadows+Into+Light|Signika|Slabo+27px|Source+Code+Pro|Source+Sans+Pro|Spectral|Titillium+Web|Ubuntu|Ubuntu+Condensed|Varela+Round|Vollkorn|Work+Sans|Yanone+Kaffeesatz|Zilla+Slab|Zilla+Slab+Highlight" rel="stylesheet">
        <style>
          .searchInput{
            height: 28px;
            width: 70%;
            margin-left: -17px;
            margin-right: 25px;
          }
        </style>
        <div class="customizer-container">
            <div id="loader-main">
                <div id="loader">
                    <div id="main">
                    </div>
                    <div class='ball-one'>
                    </div>
                    <div class='ball-two'>
                    </div>
                    <div class='ball-three'>
                    </div>
                </div>
            </div>
            <div id="mainContainer" class="row">
                <div class="col-md-2">
                    <ul class="controls">
                        <li><a href="#" class="rotateButton">view back</a></li>
                        <li style="display:none"><a href="#" class="zoomButton">Zoom In</a></li>
                        <li class="clear-btn"><a href="#" class="clearButton">clear</a></li>
                        <li class="templates-btn" style="display:none"><a href="#" class="templatesButton">templates</a></li>
                    </ul>
                </div>
                <div class="col-md-5 image-box">
                    <div class="front-image box-front prd-img-container ">
                        <img class="" src="" alt="">
                    </div>
                    <div class="back-image box-back prd-img-container ">
                        <img class="" src="" alt="">
                    </div>
                </div>
                <div class="col-md-5" id="product_details">
                    <nav>
                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                                <i class="fas fa-tshirt"></i>
                                <p>Product</p>
                            </a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                                <i class="fas fa-camera"></i>
                                <p>Add Images</p>
                            </a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">
                                <i class="fas fa-font"></i>
                                <p>Add Text</p>
                            </a>
                            <a class="nav-item nav-link" style="cursor: pointer;display:none"  id="nav-about-tab" data-toggle="modal" data-target="#LoginModal"    aria-selected="false">
                                <i class="fas fa-download"></i>
                                <p>Save/Load</p>
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="col-md-12">
                                <div id="product-details">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="panel-body upload-list">
                                <h1>Add an image to your design</h1>
                                <p>Browse our clip art catalog or upload your own image.</p>
                                <ul class="add-image-nav">
                                    <li>
                                        <a class="clipart" href="#Cliparts">
                                            <div class="arrow">
                                                <h2>Clip Art</h2>
                                                <span>Browse thousands of images</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="upload" href="#Upload">
                                            <div class="arrow">
                                                <h2>Upload Images</h2>
                                                <span>Upload your own picture or logo</span>
                                            </div>
                                        </a>
                                    </li>
                                  	<li class="img-url">
                                        <a class="upload" href="#imageUrl">
                                            <div class="arrow">
                                                <h2>Load Images</h2>
                                                <span>Load image from Web</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="myimg">
                                        <a class="myimages" href="#Myimages">
                                            <div class="arrow">
                                                <h2>My Images</h2>
                                                <span>Choose from your saved images</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="panel-body upload-grid">
                                <ul class="add-image-nav">
                                  <li class="search-bar">
                                    <a class="clipart-grid nav-click" href="#Cliparts">Clip Art</a>
                                  </li>
                                  <li>
                                    <input hidden type="file" id="file"><a class="myupload-grid nav-click load_img chooser"></a><label class="upload" for="file">Upload Images</label>
                                  </li>
                                  <li class="img-url">
                                    <a class="myupload-grid nav-click" href="#imageUrl">Load Image From Web</a>
                                  </li>
                                  <li class="myimg">
                                    <a class="myimages-grid nav-click" href="#Myimages">My Images</a>
                                  </li>
                                  <li style="display:none" class="img_customization">
                                    <a class="myimages-grid1 nav-click" href="#ImgCustom">Image Customization</a>
                                  </li>
                                </ul>

                              	<div class="upload-option" id="ImgCustom">

        							<!--           For Image Customization              -->

                                  	<table class="parent_table" data-apply="image" id="Table_style-image">
                                      <tr>
                                        <td class="slider_td1">
                                          <label class="edit-icon text-size">
                                            Width
                                          </label>
                                        </td>
                                            <td style="width:63%">
                                          <input   class="widthInput number-input range-slider__range style_slider"  value="150" max="400" min="100" pattern="[0-9]*" txb_id="widthInput" type="range"/>
                                        </td>
                                        <td>
                                          <input class="tr_input" id="widthInput" max="400" min="100"   obj_attr="width" type="text"/>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="slider_td1">
                                          <label class="edit-icon spacing">
                                            Height
                                          </label>
                                        </td>
                                        <td style="width:63%" >
                                          <input class="HeightInput range-slider__range style_slider" max="400" min="100" value="150" pattern="[0-9]*" txb_id="HeightInput" type="range"/>
                                        </td>
                                        <td>
                                          <input class="tr_input" id="HeightInput" max="400" min="100" obj_attr="height" type="text"/>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="slider_td1">
                                          <label class="edit-icon rotation">
                                            Rotate
                                          </label>
                                        </td>
                                        <td style="width:63%" >
                                          <input class="crotInput range-slider__range style_slider" max="360" min="-360"   value="-360" pattern="[0-9]*" step="any" txb_id="crotInput" type="range"/>
                                        </td>
                                        <td>
                                          <input class="tr_input" id="crotInput" max="360" min="-360" obj_attr="angle" type="text"/>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="slider_td1">
                                          <label class="edit-icon horizontalScale">
                                            Stretch
                                          </label>
                                        </td>
                                        <td style="width:63%" >
                                          <input class="chorizontalScaleInput range-slider__range style_slider" max="10"  value="0" min="0" pattern="[0-9]*" step="any" txb_id="chorizontalScaleInput" type="range"/>
                                        </td>
                                        <td>
                                          <input class="tr_input" id="chorizontalScaleInput" max="10" min="0" obj_attr="scaleX" type="text"/>
                                        </td>
                                      </tr>
                                    </table>
                                 </div>
                                <div class="upload-option" id="Cliparts">
                                  <div id="clipart-breadcrumb" class="breadcrumb">
                                      <nav aria-label="breadcrumb">
                                          <ol class="breadcrumb">
                                          </ol>
                                      </nav>
                                  </div>
                                  <div id="search-data" style="margin-bottom:16px">
                                    	<input class="searchInput" type="text" name="search-cat" placeholder="Search...">
                                      	<button type="button" style="height: 26px;font-size: smaller;padding-top:2px" id="search" name="search" class="btn btn-primary">
                                          Search
                                      </button>
                                    </div>
                                  <div class="hide_cliparts" id="loadCliparts">

                                  </div>

                              	</div>
                                <div id="imageUrl" class="upload-option" style="padding:20px">
                                  <div id="imgurl">
                                    <div id = "fileextension-error" style="display:none"> <div class="alert alert-danger">Invalid image url</div></div>
                                    <input class="load-url-img" type="text" placeholder="insert url">
                                    <button id="btn-url" class="btn btn-primary">
                                      Proceed
                                    </button>
                                  </div>
                                  <div id="loadbtn">
                                  	<button id="loadmorebtn" style="display:none" class="btn btn-primary">
                                      Load More
                                    </button>
                                  </div>

                                </div>

                                <div class="upload-option" id="Upload">

                                </div>
                                <div class="upload-option" id="Myimages">
        							<!-- HI My mages -->
                              </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <div class="Pane AddTextPane">
                                <a href="#" class="close-icon"></a>
                                <h3 style="color: #08588c;font-size: 1rem;">Enter Your Text Below</h3>
                                <textarea id="text-editor" class="text" rows="4" placeholder="-- enter your text here --" spellcheck="true" style="width:100%;background: rgb(255, 255, 255) none repeat scroll 0% 0%; z-index: auto; position: relative; line-height: 20px; font-size: 16px; transition: none 0s ease 0s;"></textarea>
                                 <button type="button" class="addText btn btn-primary">Add To Design</button>
                                <div class="text-style row text_styles" style="display:none">
                                    <div class="col-md-6">
                                        <label>Font Family</label>
                                        <div id="font-modifier">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Select Font Family <span class="caret"></span></button>
                                              <ul aria-labelledby="menu1" class="dropdown-menu" id="font" role="menu">
                                                <li role="presentation" style="font-family: Anton; font-weight: 400">
                                                  <label for="Anton">
                                                    <span class="font_style" font_style="Anton">
                                                      Anton
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Arimo; font-weight: 400">
                                                  <label for="Arimo">
                                                    <span class="font_style" font_style="Arimo">
                                                      Arimo
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Arvo; font-weight: 400">
                                                  <label for="Arvo">
                                                    <span class="font_style" font_style="Arvo">
                                                      Arvo
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Bowlby One SC; font-weight: 400">
                                                  <label for="Bowlby+One+SC">
                                                    <span class="font_style" font_style="Bowlby+One+SC">
                                                      Bowlby One SC
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Brawler; font-weight: 400">
                                                  <label for="Brawler">
                                                    <span class="font_style" font_style="Brawler">
                                                      Brawler
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Cabin; font-weight: 400">
                                                  <label for="Cabin">
                                                    <span class="font_style" font_style="Cabin">
                                                      Cabin
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Crimson Text; font-weight: 400">
                                                  <label for="Crimson+Text">
                                                    <span class="font_style" font_style="Crimson+Text">
                                                      Crimson Text
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Cuprum; font-weight: 400">
                                                  <label for="Cuprum">
                                                    <span class="font_style" font_style="Cuprum">
                                                      Cuprum
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Dancing Script; font-weight: 400">
                                                  <label for="Dancing+Script">
                                                    <span class="font_style" font_style="Dancing+Script">
                                                      Dancing Script
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Droid Sans; font-weight: 400">
                                                  <label for="Droid+Sans">
                                                    <span class="font_style" font_style="Droid+Sans">
                                                      Droid Sans
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Droid Sans Mono; font-weight: 400">
                                                  <label for="Droid+Sans+Mono">
                                                    <span class="font_style" font_style="Droid+Sans+Mono">
                                                      Droid Sans Mono
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Droid Serif; font-weight: 400">
                                                  <label for="Droid+Serif">
                                                    <span class="font_style" font_style="Droid+Serif">
                                                      Droid Serif
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: EB Garamond; font-weight: 400">
                                                  <label for="EB+Garamond">
                                                    <span class="font_style" font_style="EB+Garamond">
                                                      EB Garamond
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Francois One; font-weight: 400">
                                                  <label for="Francois+One">
                                                    <span class="font_style" font_style="Francois+One">
                                                      Francois One
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Goudy Bookletter 1911; font-weight: 400">
                                                  <label for="Goudy+Bookletter+1911">
                                                    <span class="font_style" font_style="Goudy+Bookletter+1911">
                                                      Goudy Bookletter 1911
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Gruppo; font-weight: 400">
                                                  <label for="Gruppo">
                                                    <span class="font_style" font_style="Gruppo">
                                                      Gruppo
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Inconsolata; font-weight: 400">
                                                  <label for="Inconsolata">
                                                    <span class="font_style" font_style="Inconsolata">
                                                      Inconsolata
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Indie Flower; font-weight: 400">
                                                  <label for="Indie+Flower">
                                                    <span class="font_style" font_style="Indie+Flower">
                                                      Indie Flower
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Josefin Sans; font-weight: 400">
                                                  <label for="Josefin+Sans">
                                                    <span class="font_style" font_style="Josefin+Sans">
                                                      Josefin Sans
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Lobster; font-weight: 400">
                                                  <label for="Lobster">
                                                    <span class="font_style" font_style="Lobster">
                                                      Lobster
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Lora; font-weight: 400">
                                                  <label for="Lora">
                                                    <span class="font_style" font_style="Lora">
                                                      Lora
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Maven Pro; font-weight: 400">
                                                  <label for="Maven+Pro">
                                                    <span class="font_style" font_style="Maven+Pro">
                                                      Maven Pro
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Merriweather; font-weight: 400">
                                                  <label for="Merriweather">
                                                    <span class="font_style" font_style="Merriweather">
                                                      Merriweather
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Muli; font-weight: 300">
                                                  <label for="Muli:300">
                                                    <span class="font_style" font_style="Muli:300">
                                                      Muli 300
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Nunito; font-weight: light">
                                                  <label for="Nunito:light">
                                                    <span class="font_style" font_style="Nunito:light">
                                                      Nunito light
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Open Sans; font-weight: 400">
                                                  <label for="Open+Sans">
                                                    <span class="font_style" font_style="Open+Sans">
                                                      Open Sans
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Open Sans Condensed; font-weight: 300">
                                                  <label for="Open+Sans+Condensed:300">
                                                    <span class="font_style" font_style="Open+Sans+Condensed:300">
                                                      Open Sans Condensed 300
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Oswald; font-weight: 400">
                                                  <label for="Oswald">
                                                    <span class="font_style" font_style="Oswald">
                                                      Oswald
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Pacifico; font-weight: 400">
                                                  <label for="Pacifico">
                                                    <span class="font_style" font_style="Pacifico">
                                                      Pacifico
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Play; font-weight: 400">
                                                  <label for="Play">
                                                    <span class="font_style" font_style="Play">
                                                      Play
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Playfair Display; font-weight: 400">
                                                  <label for="Playfair+Display">
                                                    <span class="font_style" font_style="Playfair+Display">
                                                      Playfair Display
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: PT Sans; font-weight: 400">
                                                  <label for="PT+Sans">
                                                    <span class="font_style" font_style="PT+Sans">
                                                      PT Sans
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: PT Sans Narrow; font-weight: 400">
                                                  <label for="PT+Sans+Narrow">
                                                    <span class="font_style" font_style="PT+Sans+Narrow">
                                                      PT Sans Narrow
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: PT Serif; font-weight: 400">
                                                  <label for="PT+Serif">
                                                    <span class="font_style" font_style="PT+Serif">
                                                      PT Serif
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Raleway; font-weight: 100">
                                                  <label for="Raleway:100">
                                                    <span class="font_style" font_style="Raleway:100">
                                                      Raleway 100
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Shadows Into Light; font-weight: 400">
                                                  <label for="Shadows+Into+Light">
                                                    <span class="font_style" font_style="Shadows+Into+Light">
                                                      Shadows Into Light
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Ubuntu; font-weight: 400">
                                                  <label for="Ubuntu">
                                                    <span class="font_style" font_style="Ubuntu">
                                                      Ubuntu
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Varela Round; font-weight: 400">
                                                  <label for="Varela+Round">
                                                    <span class="font_style" font_style="Varela+Round">
                                                      Varela Round
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Vollkorn; font-weight: 400">
                                                  <label for="Vollkorn">
                                                    <span class="font_style" font_style="Vollkorn">
                                                      Vollkorn
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Walter Turncoat; font-weight: 400">
                                                  <label for="Walter+Turncoat">
                                                    <span class="font_style" font_style="Walter+Turncoat">
                                                      Walter Turncoat
                                                    </span>
                                                  </label>
                                                </li>
                                                <li role="presentation" style="font-family: Yanone Kaffeesatz; font-weight: 400">
                                                  <label for="Yanone+Kaffeesatz">
                                                    <span class="font_style" font_style="Yanone+Kaffeesatz">
                                                      Yanone Kaffeesatz
                                                    </span>
                                                  </label>
                                                </li>
                                              </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="color-modifier" class="col-md-2">
                                        <label>Color</label>
                                        <input type="color" class="color-input colors" attr_name="color">
                                    </div>
                                    <div id="color-stroke" class="col-md-2">
                                        <label>Stroke</label>
                                        <input type="color" class="color-input colors" attr_name="stroke">
                                    </div>
                                    <div id="color-shadow" class="col-md-2">
                                        <label>Shadow</label>
                                        <input type="color" class="color-input colors" attr_name="shadow">
                                    </div>
                                </div>
                                <div class="section more row text_styles" style="display:none" >
                                  <div class="col-md-12">&nbsp;
                                     <table class="parent_table" data-apply="text" id="Table_style">
                                    <tr>
                                      <td class="slider_td1">
                                        <label class="edit-icon text-size">
                                          Size
                                        </label>
                                      </td>
                                          <td style="width:63%">
                                        <input   class="sizeInput number-input range-slider__range style_slider"  value="0" max="100" min="0" pattern="[0-9]*" txb_id="sizeInput" type="range"/>
                                      </td>
                                      <td>
                                        <input class="tr_input" id="sizeInput" max="100" min="0"   obj_attr="fontSize" type="text"/>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="slider_td1">
                                        <label class="edit-icon spacing">
                                          Spacing
                                        </label>
                                      </td>
                                      <td style="width:63%" >
                                        <input class="trackingInput range-slider__range style_slider" max="100" min="-30" value="-30" pattern="[0-9]*" txb_id="trackingInput" type="range"/>
                                      </td>
                                      <td>
                                        <input class="tr_input" id="trackingInput" max="100" min="-30" obj_attr="charSpacing" type="text"/>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="slider_td1">
                                        <label class="edit-icon rotation">
                                          Rotate
                                        </label>
                                      </td>
                                      <td style="width:63%" >
                                        <input class="rotInput range-slider__range style_slider" max="360" min="-360"   value="-360" pattern="[0-9]*" step="any" txb_id="rotInput" type="range"/>
                                      </td>
                                      <td>
                                        <input class="tr_input" id="rotInput" max="360" min="-360" obj_attr="angle" type="text"/>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="slider_td1">
                                        <label class="edit-icon leading">
                                          Ln Height
                                        </label>
                                      </td>
                                      <td style="width:63%" >
                                        <input class="leadingInput range-slider__range style_slider" max="3" min="0.5"  value="0.5"  pattern="[0-9]*" step="any" txb_id="leadingInput" type="range"/>
                                      </td>
                                      <td>
                                        <input class="tr_input" id="leadingInput" max="3" min="0.5" obj_attr="lineHeight" step="any" type="text"/>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="slider_td1">
                                        <label class="edit-icon horizontalScale">
                                          Stretch
                                        </label>
                                      </td>
                                      <td style="width:63%" >
                                        <input class="horizontalScaleInput range-slider__range style_slider" max="10"  value="0" min="0" pattern="[0-9]*" step="any" txb_id="horizontalScaleInput" type="range"/>
                                      </td>
                                      <td>
                                        <input class="tr_input" id="horizontalScaleInput" max="10" min="0" obj_attr="scaleX" type="text"/>
                                      </td>
                                    </tr>
                                  </table>
                                  </div>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab" >

                            Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui minim occaecat veniam. Fugiat veniam incididunt anim aliqua enim pariatur veniam sunt est aute sit dolor anim. Velit non irure adipisicing aliqua ullamco irure incididunt irure non esse consectetur nostrud minim non minim occaecat. Amet duis do nisi duis veniam non est eiusmod tempor incididunt tempor dolor ipsum in qui sit. Exercitation mollit sit culpa nisi culpa non adipisicing reprehenderit do dolore. Duis reprehenderit occaecat anim ullamco ad duis occaecat ex.

                      </div>
                    </div>
                  	<div>
                       <center>
                         <div style="padding:15px"><input type="checkbox" class="checkbox_click"><label style="padding-left: 8px;">Are you done customization?</label><br></div>
                         <button style="display:none" class="btn btn-primary show_btn" id="btn_save">Add to Cart</button>
                         <div id = "chooseSize" style="display:none"> <div class="alert alert-danger">Please Select Size</div></div>
                       </center>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Image Editor</h5>
                        <div id="modal-control-btns"></div>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                    <div class="modal-body">
                        <div id="tui-image-editor-container"></div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Product Selector</h5>
                        <div id="modal-control-btns"></div>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                    </div>
                    <div class="modal-body">
                        <div id="product-selector" class="row"></div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

          <div class="modal fade" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="LoginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  {% if customer  %}
                  <h5 class="modal-title" >Upload Images</h5>
                  {% else %}
                  <h5 class="modal-title" id="LoginModalLabel">Login</h5>
                  {% endif %}
                  <div id="modal-control-btns"></div>
                  <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                </div>

                {% if customer  %}
                    <div class="modal-body">
                      <input type="hidden" value="{{ customer.email }}" id="customer_email">
                      <input type="hidden" value="{{ customer.id }}" id="customer_id">
                      <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
                      <div id="drop_1">
                        <div class="dropzone-previews"></div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="submit_job" class="btn btn-primary">Submit</button>
                    </div>
                {% else %}
                <div class="modal-body">
                  <div id="CustomerLoginForm" class="form-vertical">
                    <form method="post" action="/account/login" id="customer_login" accept-charset="UTF-8"><input type="hidden" name="form_type" value="customer_login"><input type="hidden" name="utf8" value="✓">
                      <input type="hidden" name="return_to" value="/pages/create-your-own">
                      <h1 class="text-center">Login</h1><label for="CustomerEmail">Email</label>
                      <input type="email" name="customer[email]" id="CustomerEmail" class="" autocorrect="off" autocapitalize="off" autofocus="">
                      <label for="CustomerPassword">Password</label>
                      <input type="password" value="" name="customer[password]" id="CustomerPassword" class="">
                      <div class="text-center">
                        <p><a href="#recover" id="RecoverPassword">Forgot your password?</a></p>
                        <input type="submit" class="btn" value="Sign In">
                        <p>
                          <a href="/account/register" id="customer_register_link">Create account</a>
                        </p>
                      </div>
                    </form>
                  </div>
                </div>
                {% endif %}
              </div>
            </div>
          </div>

        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/customizer.js"></script>

       <?php

       $html = ob_get_clean();
       return $this->output->set_content_type('application/liquid')->set_status_header(200)->set_output($html);

    }

    public function customizeView()
    {
        $arrContextOptions = ["ssl" => ["verify_peer"=> false,"verify_peer_name" => false]];
        $value= file_get_contents(base_url()."/assets/customView.liquid", false, stream_context_create($arrContextOptions));
        return $value;
    }

    public function do_upload($FILES) {

        $this->load->library('upload');
        $dataInfo = array();
        $success  = 0;
        foreach ($FILES as $name => $FILE) {
            $a = time().mt_rand(100,1000);
            if (!empty($FILE['name'])) {
                $_FILES[$name]['name']     = $a.$FILE['name'];
                $_FILES[$name]['type']     = $FILE['type'];
                $_FILES[$name]['tmp_name'] = $FILE['tmp_name'];
                $_FILES[$name]['error']    = $FILE['error'];
                $_FILES[$name]['size']     = $FILE['size'];
                $this->upload->initialize($this->set_upload_options());
                if ($this->upload->do_upload($name)) {
                    $success++;
                    $upload_data                = $this->upload->data();
                    $dataInfo['success'][$name] = $upload_data;

                } else {
                    $dataInfo['errors'][] = array(
                        'message' => $this->upload->display_errors('', '') . '(' . $_FILES[$name]['name'] . ')',
                    );
                }
            }

        }
        return $dataInfo;
    }

    private function set_upload_options() {
        $config                  = array();
        $config['upload_path']   = 'assets/images';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 1024 * 8;
        $config['max_width']     = 1024 * 3;
        $config['max_height']    = 1024 * 3;
        $config['overwrite']     = false;
        return $config;
    }

    public function product_list()
    {

      $isValid = IsValidRequest();
      if($isValid['code'] == 200){
        $shop    = $_GET['shop'];
        $shop_id = $_GET['shop_id'];
        $sort    = ['id', 'title', 'front_image', 'back_image'];
        $search = "shop_id = ".$shop_id;
        if ($_POST['search']['value']) {
            $search .= " AND (id like '%" . $_POST['search']['value'] . "%'
           or title like '%" . $_POST['search']['value'] . "%'
           or front_image like '%" . $_POST['search']['value'] . "%'
           or back_image like '%" . $_POST['search']['value'] . "%')";
        }

        $get['fields'] = ['id', 'title', 'front_image', 'back_image', 'color_ids', 'size_ids', 'product_price'];

        if (isset($search)) {
            $get['search'] = $search;
        }

        $get['myll']   = $_POST['start'];
        $get['offset'] = $_POST['length'];
        if (isset($_POST['order'][0])) {
            $orrd         = $_POST['order'][0];
            $get['title'] = $sort[$orrd['column']];
            $get['order'] = $orrd['dir'];
        }
        $get['table']   = 'products';
        $get['shop_id'] = $_GET['shop_id'];
        $list           = $this->Global_model->GetSingleDataRecord($get);
        $cc             = $list['count'];
        $data           = array();
        $no             = $_POST['start'];
        $total_rec      = array_pop($list);
        $data           = array();

        foreach ($list as $product) {
            $row = array();

            $colors        = explode(',', $product->color_ids);
            if ($colors[0] != '') {
              $color_content = '';
              foreach ($colors as $color) {
                $color_content .= '<div class="color swatch-box" style="background:' . $color . '"></div>';
              }
            }else {
              $color_content = "<center>-</center>";
            }

            $sizes        = explode(',', $product->size_ids);
            if ($sizes[0] != '') {
              $size_content = '';
              foreach ($sizes as $size) {
                  $size_content .= '<div class="size swatch-box">' . $size . '</div>';
              }
            }else {
              $size_content = "<center>-</center>";
            }

            $row[] = $product->title;
            $row[] = '<img class="lazyload" src="' . image_thumb($product->front_image, 100, 100) . '" width="100px">';
            $row[] = '<img class="lazyload" src="' . image_thumb($product->back_image, 100, 100) . '" width="100px">';
            $row[] = $color_content;
            $row[] = $size_content;
            $row[] = $product->product_price;
            $row[] = '<a href="' . base_url('Home/EditProductView?shop=' . $shop . '&shop_id=' . $shop_id . '&product_id=' . $product->id) . '" data-id="' . $product->id . '" class="btn btn-primary btn-md"  data-target="#edit" title="Edit"><span class="fa fa-pencil"></span></a>
            <button data-id="' . $product->id . '" class="btn btn-danger btn-md delete-product"  data-placement="top" data-toggle="modal" data-target="#delete" title="Delete"><span class="fa fa-trash"></span></button>';
            $data[] = $row;
        }
        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $cc,
            "recordsFiltered" => $total_rec,
            "data"            => $data,
        );
        json_send($output);
      }else{
        $output = ["draw" => 0,"recordsTotal"    => 0,"recordsFiltered" => 0,"data"=> [],"code"=> 100,"msg"=>$isValid['msg']];
        json_send($output);
      }



    }

    public function EditProductView()
    {
        if (isset($_GET['shop'])) {
            $shop       = $_GET['shop'];
            $product_id = $_GET['product_id'];
            $shop_id    = shop_id($shop);
            $data['shop']       = $shop;
            $data['shop_id']    = $shop_id;
            $data['product_id'] = $product_id;
            $data['products']   = $this->Global_model->getRow(array('id' => $product_id, 'shop_id' => $shop_id), 'products');
            $this->load->load_admin('templates/edit_product', $data);
        } else {
            $this->load->load_admin('errors/shop-errors/shop-not-found');
        }

    }

    public function EditProduct() {
        if (!empty($_GET['shop'])) {
            $shop       = $_GET['shop'];
            $shop_id    = shop_id($shop);
            $shopAccess = getShop_accessToken_byShop($shop);
            $this->load->library('Shopify', $shopAccess);
            $dataInsert = array();

            $get_product = $this->Global_model->getRow(array('title' => $_POST['product_title']), 'products');

            if (isset($get_product->id) && $get_product->title != $_POST['product_title']) {
                $send_data['code']     = 201;
                $send_data['errors'][] = array(
                    'message' => 'Product already exist with this title!',
                );
                json_send($send_data);
                exit;
            }

            $has_files = 0;
            foreach ($_FILES as $key => $file) {
                if (!empty($file['name'])) {
                    $has_files++;
                }
            }

            if ($has_files > 0) {
              echo "if";
                $dataInfo = $this->do_upload($_FILES);
                if (isset($dataInfo['errors'])) {

                    if (isset($dataInfo['success'])) {
                        foreach ($dataInfo['success'] as $file) {
                            $path = 'assets/images/' . $file['file_name'];
                            unlink($path);
                        }
                    }

                    $dataInfo['code'] = 201;
                    json_send($dataInfo);
                    exit;
                }

                $images = $dataInfo['success'];
                // echo "<pre>";
                // print_r($images);
                // exit;
                $images_ = array();
                foreach ($images as $key => $file) {
                    $dataInsert[$key] = $file['file_name'];
                    $base64Data = $this->encodeImage($file['file_name']);
                    $images_[] = array(
                        "attachment" => $base64Data,
                        "alt" => $key,
                    );
                }
                if ($key == 'front_image' && $_POST['editimages'] == 'frontBack') {
                  $base64Data = $this->encodeImage($get_product->back_image);
                  $images_[] = array(
                    "attachment" => $base64Data,
                    "alt" => 'back_image',
                  );
                }
                if ($key == 'back_image' && $_POST['editimages'] == 'frontBack') {
                  $base64Data = $this->encodeImage($get_product->front_image);
                  if (count($images_) < 2) {
                    $images_[] = array(
                      "attachment" => $base64Data,
                      "alt" => 'front_image',
                    );
                    $images_ = array_reverse($images_);
                  }
                }
            }else {
              if ($_POST['editimages'] == 'frontBack') {
                $imgData = [$get_product->front_image,$get_product->back_image];
                $imgKey  = ['front_image','back_image'];
                for ($i=0; $i < count($imgData) ; $i++) {
                  $base64Data = $this->encodeImage($imgData[$i]);
                  $images_[] = array(
                    "attachment" => $base64Data,
                    "alt" => $imgKey[$i],
                  );
                }
              }else {
                // echo "front_image";
                // exit;
                $base64Data = $this->encodeImage($get_product->front_image);
                $images_[] = array(
                  "attachment" => $base64Data,
                  "alt" => "front_image",
                );
                $pathwithimage = 'assets/images/' . $get_product->back_image;
                unlink($pathwithimage);
              }
            }
            // print_r($images_);
            // exit;
            $size_array = explode(',', $_POST['size_array']);
            $variants = array();

            foreach ($size_array as $size) {
              array_push($variants, [
                "option1" => $size,
                "price" => $_POST['product_price']
              ]);
            }
            $product_data = array(
                "product" => array(
                    "title"        => $_POST['product_title'],
                    "body_html"    => $_POST['description'],
                    "variants"     => $variants,
                    "options"      => array(
                      array(
                        "name"    =>  "Size",
                        "values"  => $size_array,
                      ),
                    ),
                    "images"       => $images_,
                ),
            );
            $product = $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/'.getYear().'/products/'.$_POST['productId'].'.json', 'DATA' => $product_data], true);

            $dataInsert['title']       = $_POST['product_title'];
            $dataInsert['color_ids']   = implode(',', array_unique($_POST['productcolor']));
            $dataInsert['size_ids']    = implode(',', array_unique(explode(',', $_POST['size_array'])));
            $dataInsert['description'] = $_POST['description'];
            $dataInsert['product_price'] = $_POST['product_price'];
            // $dataInsert['cust_option'] = $_POST['cust_option'];
            if ($_POST['editimages'] == 'front') {
              $dataInsert['back_image'] = '';
            }

            $where = array('id' => $_POST['product_id']);
            // print_r($dataInsert);
            // exit;
            $productupdate = $this->Global_model->update_data($where, 'products', $dataInsert);

            if ($productupdate) {
                $send_data = array('code' => 200, 'message' => 'Product Updated!');
            } else {
                $send_data = array('code' => 200, 'message' => 'Product Update Failed!');
            }

            $send_data['shop']    = $shop;
            $send_data['shop_id'] = $shop_id;

            json_send($send_data);

        } else {
            $this->load->load_admin('errors/shop-errors/shop-not-found');
        }
    }
    public function encodeImage($imgData)
    {
      $path = base_url('assets/images/' .$imgData);
      $type = pathinfo($path, PATHINFO_EXTENSION);
      $data = file_get_contents($path);
      $base64 = base64_encode($data);
      return $base64;
    }

    public function hasChild($table,$id) {
      $data = $this->db->select('*')
                       ->from($table)
                       ->where("id = '$id'")
                       ->get()->row();

      $hasChild = $this->db->select('*')->from($table)->where('parent_cat', $data->id)->get()->row();
      if ($hasChild) {
        return "Child";
      } else {
        return "Empty";
      }
    }

    public function delete_record() {
      $id      = $_POST['id'];
      $table   = $_POST['table'];
      if (isset($_GET['shop'])) {
        $isValid = IsValidRequest();
        if($isValid['code'] == 200){
          if ($table == 'categories') {
            $child = $this->hasChild($table,$id);
            if ($child == 'Empty') {
              $shop    = $_GET['shop'];
              $shop_id = shop_id($shop);
              $shopAccess = getShop_accessToken_byShop($shop);
              $this->load->library('Shopify', $shopAccess);
              $get_product_id = $this->db->query("select * from products where id = '".$id."' ")->result();
              if (count($get_product_id) > 0) {
                $product_id = $get_product_id[0]->product_id;
                $test  = $this->shopify->call(['METHOD' => 'DELETE ', 'URL' => '/admin/api/'.getYear().'/products/'.$product_id.'.json'], true);
              }
              $res     = $this->Global_model->delete_row($table, array('id' => $id));

              if (!$res) {
                $response = ['code' => 200,'msg'=> 'Record deleted!'];
              } else {
                $response = ['code' => 100,'msg'  => 'Something went wrong!'];
              }
              json_send($response);
            } else {
              $response = ['code' => 100,'msg'  => 'Cannot delete, Contain child categories'];
              json_send($response);
            }
          } else {
            $shop    = $_GET['shop'];
            $shop_id = shop_id($shop);
            $shopAccess = getShop_accessToken_byShop($shop);
            $this->load->library('Shopify', $shopAccess);
            $get_product_id = $this->db->query("select * from products where id = '".$id."' ")->result();
            if (count($get_product_id) > 0) {
              $product_id = $get_product_id[0]->product_id;
              $test  = $this->shopify->call(['METHOD' => 'DELETE ', 'URL' => '/admin/api/'.getYear().'/products/'.$product_id.'.json'], true);
            }
            $res     = $this->Global_model->delete_row($table, array('id' => $id));

            if (!$res) {
              $response = ['code' => 200,'msg'=>'Record deleted!'];
            } else {
              $response = [ 'code' => 100,'msg'  => 'Something went wrong!'];
            }
            json_send($response);
          }
      }else{
        json_send($isValid);
      }

      } else {
        $this->load->load_admin('errors/shop-errors/shop-not-found');
      }
    }



    public function installInstruct()
    {
      $this->load->load_admin('instruction/install_instruct');
    }
    public function create_catergory()
    {
        if (isset($_GET['shop'])) {
          $isValid = IsValidRequest();
            if($isValid['code'] == 200){
                $post = $_POST;
                if (!empty($_FILES['cat_image']['name'])) {
                    $response = $this->do_upload($_FILES);
                    if (isset($response['success'])) {
                        $post['cat_image'] = $response['success']['cat_image']['file_name'];
                    }
                }
                $shop    = $_GET['shop'];
                $shop_id = shop_id($shop);
                $data['shop']    = $shop;
                $data['shop_id'] = $shop_id;
                $cat_data               = array();
                $cat_data['cat_name']   = $_POST['cat_name'];
                $cat_data['parent_cat'] = $_POST['parent_cat'];
                $cat_data['shop_id']    = $shop_id;

                $exists = $this->Global_model->if_exists($cat_data, 'categories');
                if (!$exists) {
                    $post['shop_id'] = $shop_id;
                    $categories      = $this->Global_model->add_category($post);
                    if ($categories) {
                        json_send(['code' => 200, 'message' => 'Category added!']);
                    }
                } else {
                    json_send(['code' => 100, 'message' => 'Category already exists!']);
                }
          }else{
            json_send($isValid);
          }

        } else {
            $this->load->load_admin('errors/shop-errors/shop-not-found');
        }
    }

    public function edit_catergory()
    {
        if (isset($_GET['shop'])) {
          $isValid = IsValidRequest();
            if($isValid['code'] == 200){
            $post = $_POST;
            if (!empty($_FILES['cat_image']['name'])) {
                $response = $this->do_upload($_FILES);
                if (isset($response['success'])) {
                    $post['cat_image'] = $response['success']['cat_image']['file_name'];
                }
            }

            $shop    = $_GET['shop'];
            $shop_id = shop_id($shop);

            $data['shop']    = $shop;
            $data['shop_id'] = $shop_id;
            $cat_data               = array();
            $cat_data['cat_name']   = $_POST['cat_name'];
            $cat_data['parent_cat'] = $_POST['parent_cat'];
            $cat_data['shop_id']    = $shop_id;

            $exists = $this->Global_model->getRow($cat_data, 'categories');
            if (isset($exists->id) && $exists->cat_name != $_POST['cat_name']) {
                json_send(['code' => 100, 'message' => 'Category already exists!']);
            }else{
                $post['shop_id'] = $shop_id;
                $categories      = $this->Global_model->update_category($post);
                if ($categories) {
                    json_send(['code' => 200,'message' => 'Category updated!']);
                }
            }
          }else{
            json_send($isValid);
          }

        }else{
            $this->load->load_admin('errors/shop-errors/shop-not-found');
        }
    }

    public function ClipArtView()
    {
        if (isset($_GET['shop'])) {
            $shop    = $_GET['shop'];
            $shop_id = shop_id($shop);

            $data['shop']       = $shop;
            $data['shop_id']    = $shop_id;
            $data['categories'] = $this->Global_model->get_all_categories($shop_id);
            $data['table']      = 'categories';
            $this->load->load_admin('templates/categories', $data);
        } else {
            $this->load->load_admin('errors/shop-errors/shop-not-found');
        }

    }

    protected function getCategoryTree($level = 0, $prefix = '')
    {
        $shop_id = shop_id($_GET['shop']);
        $rows     = $this->Global_model->get_category_by_level($level, $shop_id);
        $category = '<ul class="clipart-cat">';
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $category .= '<li>';
                $category .= '<input name="cat_ids[]" class="cat_id" type="checkbox" id="cat-' . $row->id . '" value="' . $row->id . '"/>';
                $category .= '<label for="cat-' . $row->id . '">' . $prefix . $row->cat_name . '</label>';
                $category .= '</li>';
                $sub_category = $this->Global_model->get_category_by_level($row->id, $shop_id);
                if (count($sub_category) > 0) {
                    $category .= '<li>';
                    $category .= '<ul class="clipart-sub-cat">';
                    foreach ($sub_category as $sub_cat) {
                        $category .= '<li>';
                        $category .= '<input name="cat_ids[]" class="cat_id" type="checkbox" id="cat-' . $sub_cat->id . '" value="' . $sub_cat->id . '"/>';
                        $category .= '<label for="cat-' . $sub_cat->id . '">' . $sub_cat->cat_name . '</label>';
                        $category .= '</li>';
                    }
                    $category .= '</ul>';
                    $category .= '</li>';
                }
            }
        }
        $category .= '</ul>';
        return $category;
    }

    protected function getCategoryHeirachy($level = 0, $prefix = '')
    {
        $shop_id = shop_id($_GET['shop']);
        $rows     = $this->Global_model->get_category_by_level($level, $shop_id);
        $category = array();
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                if (!in_array($row->cat_name, $category)) {
                    $category[$row->cat_name] = array();
                }
                $category[$row->cat_name]['id'] = $row->id;

                $sub_category = $this->Global_model->get_category_by_level($row->id, $shop_id);
                if (count($sub_category) > 0) {
                    foreach ($sub_category as $sub_cat) {
                        $subcat_data                                = array();
                        $subcat_data['id']                          = $sub_cat->id;
                        $subcat_data['title']                       = $sub_cat->cat_name;
                        $category[$row->cat_name]['sub_category'][] = $subcat_data;
                    }

                }
            }
        }
        return $category;
    }

    public function DatatableCatTree($value = '') {
      $isValid = IsValidRequest();
      if($isValid['code'] == 200){
        $shop    = $_GET['shop'];
        $shop_id = $_GET['shop_id'];
        $sort    = ['id', 'parent_cat', 'cat_name', 'cat_image'];
        $search = "shop_id = ".$shop_id;
        if ($_POST['search']['value']) {
            $search .= " AND (id like '%" . $_POST['search']['value'] . "%'
           or parent_cat like '%" . $_POST['search']['value'] . "%'
           or cat_name like '%" . $_POST['search']['value'] . "%'
           or cat_image like '%" . $_POST['search']['value'] . "%')";
        }

        $get['fields'] = ['id', 'parent_cat', 'cat_name', 'cat_image'];

        if (isset($search)) {
            $get['search'] = $search;
        }

        $get['myll']   = $_POST['start'];
        $get['offset'] = $_POST['length'];

        if (isset($_POST['order'][0])) {
            $orrd         = $_POST['order'][0];
            $get['title'] = $sort[$orrd['column']];
            $get['order'] = $orrd['dir'];
        }

        $get['table']   = 'categories';
        $get['shop_id'] = $_GET['shop_id'];
        $list           = $this->Global_model->GetSingleDataRecord($get);

        $cc             = $list['count'];
        $data           = array();
        $no             = $_POST['start'];
        $total_rec      = array_pop($list);
        $data           = array();

        foreach ($list as $cat) {
            $row   = array();
            $image = $cat->cat_image;

            $row[] = '<img src="' . image_thumb($image, 50, 50) . '" width="50px">';
            $row[] = $cat->cat_name;

            if ($cat->parent_cat > 0) {
                $sub_cat = $this->Global_model->get_category_by_id($cat->parent_cat);
                $row[]   = $sub_cat->cat_name;
            } else {
                $row[] = '--';
            }

            $row[] = $this->Global_model->get_num_rows('clip_arts', array('cat_id' => $cat->id));
            $row[] = '
            <button data-id="' . $cat->id . '" class="btn btn-primary btn-md edit-product"  data-placement="top" data-toggle="modal" data-target="#edit" title="Edit"><span class="fa fa-pencil"></span></button>
            <button data-id="' . $cat->id . '" class="btn btn-danger btn-md delete-category_product"><span class="fa fa-trash"></span></button>';
            $data[] = $row;
        }
        $output =["draw"=> $_POST['draw'],"recordsTotal"=>$cc,"recordsFiltered" => $total_rec,"data"=> $data];
        json_send($output);
      }else{
          $output = ["draw" => 0,"recordsTotal"    => 0,"recordsFiltered" => 0,"data"=> [],"code"=> 100,"msg"=>$isValid['msg']];
          json_send($output);
        }
    }

    public function get_category_by_id($cat_id = '')
    {
      $isValid = IsValidRequest();
      if($isValid['code'] == 200){
        if (empty($cat_id)) {
            $cat_id = $_GET['cat_id'];
        }
        json_send($this->Global_model->get_category_by_id($cat_id));
      }else{
        json_send($isValid);
      }
    }

    public function AddClipArtView()
    {
        if (!isset($_GET['shop'])) {
            $this->load->load_admin('errors/shop-errors/shop-not-found');
            exit;
        } else {
            $shop = $_GET['shop'];
        }

        $data = array(
            'shop'         => $_GET['shop'],
            'shop_id'      => shop_id($_GET['shop']),
            'CategoryTree' => $this->getCategoryTree(),
        );
        $this->load->load_admin('templates/AddClipArtView', $data);
    }

    public function search_clipart() {
        if (!isset($_GET['shop'])) {
            $this->load->load_admin('errors/shop-errors/shop-not-found');
            exit;
        } else {
            $shop = $_GET['shop'];
        }

        $post_data            = $_POST;
        $post_data['shop_id'] = $shop_id = shop_id($_GET['shop']);

        $limit               = (isset($post_data['show_per_page'])) ? (int) $post_data['show_per_page'] : 10;
        $page                = (isset($_GET['per_page'])) ? $_GET['per_page'] : 0;
        $post_data['offset'] = $offset = ($page > 0) ? $limit * ($page - 1) : 0;

        $cliparts = $this->Global_model->search_clipart($post_data);

        $data = array(
            'shop'       => $_GET['shop'],
            'shop_id'    => $shop_id,
            'cliparts'   => $cliparts,
            'categories' => $this->getCategoryHeirachy(),
        );

        $num_rows           = $this->Global_model->get_rows_count($post_data);
        $base_url           = base_url('Home/search_clipart');
        $data['pagination'] = $this->get_pagination($num_rows, $limit, $base_url);
        $this->load->load_admin('templates/cliparts', $data);
    }

    public function cliparts() {
        if (!isset($_GET['shop'])) {
            $this->load->load_admin('errors/shop-errors/shop-not-found');
            exit;
        } else {
            $shop = $_GET['shop'];
        }

        $shop_id = shop_id($_GET['shop']);
        if(isset($_POST['ajax'])){
          $isValid = IsValidRequest();
          if($isValid['code'] == 200){
            $where    = (isset($_GET['cat_id']) && $_POST['cat_id'] != '-1') ? array('cat_id' => $_POST['cat_id']) : array('shop_id' => $shop_id);
            $limit    = (isset($_POST['per_page'])) ? (int) $_POST['per_page'] : 10;
            $page     = (isset($_GET['per_page'])) ? $_GET['per_page'] : 0;
            $offset   = ($page > 0) ? $limit * ($page - 1) : 0;
            $cliparts = $this->Global_model->get_cliparts($limit, $offset, $where);
            $data     = ['shop'=> $_GET['shop'],'shop_id'=> $shop_id,'cliparts'=> $cliparts,'categories' => $this->getCategoryHeirachy()];
            $num_rows           = $this->Global_model->get_num_rows('clip_arts', $where);
            $base_url           = base_url('Home/cliparts');
            $data['pagination'] = $this->get_pagination($num_rows, $limit, $base_url);
            $this->load->load_admin('templates/cliparts', $data);
          }else{
              // json_send($isValid);
              echo implode('-',$isValid);
          }
        }else{
          $where    = (isset($_GET['cat_id']) && $_POST['cat_id'] != '-1') ? array('cat_id' => $_POST['cat_id']) : array('shop_id' => $shop_id);
          $limit    = (isset($_POST['per_page'])) ? (int) $_POST['per_page'] : 10;
          $page     = (isset($_GET['per_page'])) ? $_GET['per_page'] : 0;
          $offset   = ($page > 0) ? $limit * ($page - 1) : 0;
          $cliparts = $this->Global_model->get_cliparts($limit, $offset, $where);
          $data     = ['shop'=> $_GET['shop'],'shop_id'=> $shop_id,'cliparts'=> $cliparts,'categories' => $this->getCategoryHeirachy()];
          $num_rows           = $this->Global_model->get_num_rows('clip_arts', $where);
          $base_url           = base_url('Home/cliparts');
          $data['pagination'] = $this->get_pagination($num_rows, $limit, $base_url);
          $this->load->load_admin('templates/cliparts', $data);
        }

    }

    public function filter_cliparts()
    {
        // _debug($_POST);

      $isValid = IsValidRequest();
      if($isValid['code'] == 200){
        $post_data            = $_POST;
        $post_data['shop_id'] = $shop_id = shop_id($_GET['shop']);

        $limit                      = (isset($post_data['per_page'])) ? (int) $post_data['per_page'] : 10;
        $where                      = (isset($post_data['cat_id']) && $post_data['cat_id'] != '-1') ? array('cat_id' => $post_data['cat_id']) : array('shop_id' => $shop_id);
        $post_data['offset']        = $offset        = (isset($post_data['offset']) && !empty($post_data['offset'])) ? $post_data['offset'] : 0;
        $post_data['show_per_page'] = $post_data['per_page'];

        if (isset($post_data['search_query']) && !empty($post_data['search_query'])) {
            $cliparts = $this->Global_model->search_clipart($post_data);
        } else {
            $cliparts = $this->Global_model->get_cliparts($limit, $offset, $where);
        }

        $clip_images = '';

        foreach ($cliparts as $clipart) {
            $img_id = str_replace('=', '', app_serialize('clipart-' . $clipart->id));
            $clip_images .= '<div class="image-wrapper imgContainer">
                               <a class="fancybox"  id="' . $img_id . '" data-fancybox="gallery" data-filter="CAT-' . $clipart->id . '" href="' . ASSETS . 'images/' . $clipart->clip_art . '">
                               <img class="lazyload" style="background-image: url(' . ASSETS . 'images/dummy-image.gif' . ')" data-src="' . ASSETS . 'images/' . $clipart->clip_art . '" alt="" /></a>
                              <div class="image-overlay">
                                <button type="button" class="btn btn-primary open-lightbox" data-img-id="' . $img_id . '"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-danger delete-clipart" data-clip-id="' . $clipart->id . '"><i class="fa fa-trash"></i></button>
                              </div>
                            </div>  ';
        }
        $data = array(
            'clip_images' => $clip_images,
        );

        if (isset($post_data['search_query']) && !empty($post_data['search_query'])) {
            $num_rows = $this->Global_model->get_rows_count($post_data);
        } else {
            $num_rows = $this->Global_model->get_num_rows('clip_arts', $where);
        }

        $base_url           = base_url('Home/cliparts');
        $data['pagination'] = $this->get_pagination($num_rows, $limit, $base_url);
        json_send($data);
      }else{
        json_send($isValid);
      }

    }

    protected function get_pagination($num_rows = 0, $limit = 10, $base_url)
    {
        $this->load->library('pagination');
        $config['base_url']   = $base_url;
        $config['total_rows'] = $num_rows;
        $config['per_page']   = $limit;

        // custom paging configuration
        $config['num_links']            = 2;
        $config['use_page_numbers']     = true;
        $config['reuse_query_string']   = true;
        $config['enable_query_strings'] = true;
        $config['page_query_string']    = true;

        $config['full_tag_open']  = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link']      = '<<';
        $config['first_tag_open']  = '<li class="firstlink">';
        $config['first_tag_close'] = '</li>';

        $config['last_link']      = '>>';
        $config['last_tag_open']  = '<li class="lastlink">';
        $config['last_tag_close'] = '</li>';

        $config['next_link']      = '>';
        $config['next_tag_open']  = '<li class="nextlink">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link']      = '<';
        $config['prev_tag_open']  = '<li class="prevlink">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open']  = '<li class="curlink"><a class="active" href="#0">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open']  = '<li class="numlink">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    public function upload_clipart() {
        $exists = $this->Global_model->if_exists(array('clip_art' => $_FILES['file']['name']), 'clip_arts');
        if ($exists == 0) {
            $response = $this->do_upload($_FILES);
            if (isset($response['success'])) {
                $file_name = $response['success']['file']['file_name'];
                $cat_ids   = implode(',', $_POST['cat_ids']);
                $shop_id = shop_id($_GET['shop']);

                $this->Global_model->insert_data('clip_arts', array('clip_art' => $file_name, 'cat_id' => $cat_ids, 'shop_id' => $shop_id));
            }
        }
        json_send(array('code' => 200));

    }


    public function get_cliparts_html() {
        // _debug(allowed_cors_origins());
        // exit;
        _debug(md5('PRINTIFY_admin@123#'));
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_RETURNTRANSFER => 1,
        //     CURLOPT_URL => urlencode('https://www.uberprints.com/studio/brain/clipartlist/Design Elements?format=json'),
        // ));
        // $resp = curl_exec($curl);
        // curl_close($curl);

        // $result = file_get_contents('https://www.uberprints.com/studio/brain/clipartlist/Design Elements?format=json');

        // $html = str_get_html($resp);
        // $categories = $html->find('.AddImagePane')[0]->find('.ClipArtCatalog')[0]->innertext;

        // _debug(json_decode($result, true));

        // foreach ($categories as $category) {
        //     $url = 'http://clipart-library.com';
        //     $cat_title = $category->find('p',0)->innertext;

        //     $cat_data = array();
        //     $cat_data['cat_name']   = $cat_title;
        //     $cat_data['parent_cat'] = 0;
        //     $cat_data['shop_id']    = 0;
        //     $exists = $this->Global_model->if_exists($cat_data,'categories');

        //     if ($exists) {
        //         continue;
        //     }

        //     $cat_image = $category->find('img',0)->src;
        //     $cat_image_str = explode('.', $cat_image);
        //     $img = 'assets/images/'.$this->slugify($cat_title).'.'.$cat_image_str[1];
        //     file_put_contents($img, file_get_contents($url.$cat_image));

        //     $cat_collec = $category->find('a',0)->href;

        //     $this->db->insert('categories',
        //         array(
        //             'cat_name'=>$cat_title,
        //             'parent_cat'=>0,
        //             'shop_id' => 0,
        //             'cat_image' => $this->slugify($cat_title).'.'.$cat_image_str[1]
        //         )
        //     );
        //     $cat_id = $this->db->insert_id();

        //     $curl1 = curl_init();
        //     curl_setopt_array($curl1, array(
        //         CURLOPT_RETURNTRANSFER => 1,
        //         CURLOPT_URL => 'http://clipart-library.com'.$cat_collec,
        //     ));
        //     $resp1 = curl_exec($curl1);
        //     curl_close($curl1);

        //     $html1 = str_get_html($resp1);
        //     $cliparts = $html1->find('div.thumbnails',0)->find('.thumbnail');

        //     foreach ($cliparts as $clipart) {
        //         $clip_title = $clipart->find('li',0)->find('span',0)->innertext;
        //         $clip_image = $clipart->find('img',0)->src;
        //         $clip_str = explode('.', $clip_image);
        //         $ext = end($clip_str);
        //         $img2 = 'assets/images/'.$this->slugify($clip_title).'.'.$ext;
        //         $sContents = str_replace('../', '/', $clip_image);
        //         file_put_contents($img2, file_get_contents($url.$sContents));

        //         $this->db->insert('clip_arts',
        //             array(
        //                 'clip_art'=>$this->slugify($clip_title).'.'.$ext,
        //                 'cat_id'=>$cat_id
        //             )
        //         );
        //     }

        // }

    }

    protected function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
    public function SyncProducts()
    {
      if (!empty($_GET['shop'])) {
          $shop            = $_GET['shop'];
          $shopAccess = getShop_accessToken_byShop($shop);
          $access_token = $shopAccess['ACCESS_TOKEN'];
          $items_per_page = 100;
          $merged = array();
          $next_page = '';
          $last_page = false;
          while(!$last_page) {
          	$url = 'https://'.$shop.'/admin/api/'.getYear().'/products.json?limit=' . $items_per_page . $next_page.'&access_token='."$access_token" ;//. '&fields=id,title,options,images,variants';
          	$curl = curl_init();
          	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
          	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          	curl_setopt($curl, CURLOPT_URL, $url);
          	curl_setopt($curl, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$headers) {
          	    $len = strlen($header);
          	    $header = explode(':', $header, 2);
          	    if (count($header) >= 2) {
          	        $headers[strtolower(trim($header[0]))] = trim($header[1]);
          	    }
          	    return $len;
          	});
          	$result = curl_exec($curl);
          	curl_close($curl);

          	if(isset($headers['link'])) {
          		$links = explode(',', $headers['link']);
          		foreach($links as $link) {
          			if(strpos($headers['link'], 'rel="next"')) {
          				preg_match('~<(.*?)>~', $link, $next);
          				$url_components = parse_url($next[1]);
          				parse_str($url_components['query'], $params);
          				$next_page = '&page_info=' . $params['page_info'];
          			} else {
          				$last_page = true;
          			}
          		}
          	} else {
                          $last_page = true; // if missing "link" parameter - there's only one page of results = last_page
                  }
          	$source_array = json_decode($result, true);
          	$merged = array_merge_recursive($merged, $source_array);
          }

          $data['product_data'] = $merged['products'];
          $data['shop']    = $shop;
          $data['shop_id'] = shop_id($shop);
          $this->load->load_admin('templates/sync_products', $data);
      } else {
          $this->load->load_admin('errors/shop-errors/shop-not-found');
      }
    }
    public function InsertSyncProduct()
    {
      if (!empty($_GET['shop'])) {
        $shop            = $_GET['shop'];
        $shopAccess = getShop_accessToken_byShop($shop);
        $this->load->library('Shopify', $shopAccess);
        $shop_id = shop_id($shop);
        $selected_array = $_POST['selected_array'];
        foreach ($selected_array as $value) {
          $get_product_count = $this->db->query("select product_count from products where shop_id='".$shop_id."' order by id desc limit 1")->result();
          if (count($get_product_count) > 0) {
            $get_product_count1 = $get_product_count[0]->product_count;
            $product_count = $get_product_count1 + 1;
          } else {
            $product_count = 1;
          }
          $check_pid_exist = $this->Global_model->check_pid_exist($value['product_id'],$shop_id);
          if ($check_pid_exist) {
            $data['code'] = 200;
            $data['msg'] = 'Product Already Sync';
          }else {
            if ($value['front_image'] != '') {
              $imageName = "_front".time().str_shuffle("abghghg@$@43432").mt_rand(100,1000);
              $front_image1 = $this->base64Image($value['front_image'],$imageName);
              $front_image = $front_image1 ;
            }else {
              $front_image = "";
            }
            if ($value['back_image'] != '') {
              $imageName = "_back".time().str_shuffle("abghghg@$@43432").mt_rand(100,1000);
              $back_image1 = $this->base64Image($value['back_image'],$imageName);
              $back_image = $back_image1 ;
            }else {
              $back_image = "";
            }
            //Add Template Suffix
            $product_data = array(
              "product" => array(
                "template_suffix" => "stensiledv2"
              ),
            );
            $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/'.getYear().'/products/'.$value['product_id'].'.json', 'DATA' => $product_data], true);
            //For Product Collection
            $check_collection_id = $this->db->query("select collection_id from product_collection where shop_id='".$shop_id."' ")->result();
            if (count($check_collection_id) > 0) {
              $collection_id = $check_collection_id[0]->collection_id;
              $this->product_collection($collection_id,$value['product_id'],$shop);
            } else {
              $create_collection = array("custom_collection" => array("title" => "StensiledV2"),);
              $year = getYear();
              $get_collection = $this->shopify->call(['METHOD' => 'POST', 'URL' => '/admin/api/'.$year.'/custom_collections.json', 'DATA' => $create_collection], true);
              $get_collection_id = $get_collection->custom_collection;
              $insertData = array(
                "collection_id" => $get_collection_id->id,
                "shop_id"       => $shop_id
              );
              $this->db->insert("product_collection",$insertData);
              $this->product_collection($get_collection_id->id,$value['product_id'],$shop);
            }
            $dataArray = array(
              'title' => $value['title'],
              'handle' => $value['pro_handle'],
              'product_id' => $value['product_id'],
              'variant_id' => $value['variant_id'],
              'description' => $value['description'],
              'front_image' => $front_image,
              'back_image' => $back_image,
              'color_ids' => $value['color'],
              'size_ids' => $value['size'],
              'product_price' => $value['price'],
              'shop_id' => $shop_id,
              'product_count' => $product_count,
            );
            $insertData = $this->Global_model->InsertSyncProduct('products',$dataArray);
            if ($insertData) {
              $data['code'] = 200;
              $data['msg'] = 'Product Sync Successfully';
            }else {
              $data['code'] = 100;
              $data['msg'] = 'Something went wrong';
            }
          }
        }
        echo json_send($data);
      } else {
          $this->load->load_admin('errors/shop-errors/shop-not-found');
      }
    }
    public function base64Image($checkedData,$imageName)
    {
      $pathName = str_replace('index.php','',$_SERVER['SCRIPT_NAME']);
      $upload_dir = $_SERVER['DOCUMENT_ROOT'].$pathName.'assets/images/';
      $type = pathinfo($checkedData, PATHINFO_EXTENSION);
      $str = substr($type, strpos($type, '?'));
      $fileType = str_replace($str,'',$type);
      $data = file_get_contents($checkedData);
      $base64 = 'data:image/'.$fileType.';base64,' . base64_encode($data);
      $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
      $fileFile = $upload_dir.$imageName.'.'.$fileType;
      $file = file_put_contents($fileFile, $data);
      return $imageName.'.'.$fileType;
    }
    public function asyncProduct()
    {
      if (!empty($_GET['shop'])) {
        $shop            = $_GET['shop'];
        $shopAccess = getShop_accessToken_byShop($shop);
        $this->load->library('Shopify', $shopAccess);
        $shop_id = shop_id($shop);
        $selected_array = $_POST['selected_array'];
        foreach ($selected_array as $value) {
          //Add Template Suffix
          $product_data = array(
            "product" => array(
              "template_suffix" => ""
            ),
          );
          $this->shopify->call(['METHOD' => 'PUT', 'URL' => '/admin/api/'.getYear().'/products/'.$value['product_id'].'.json', 'DATA' => $product_data], true);
          $getimage = $this->Global_model->check_pid_exist($value['product_id'],$shop_id);
          $front_image = $getimage[0]->front_image;
          $back_image = $getimage[0]->back_image;
          $deleteData = $this->Global_model->asyncProduct($value['product_id'],$shop_id);
          if ($deleteData) {
            if (!empty($front_image)) {
              $funlink = 'assets/images/' . $front_image;
              unlink($funlink);
            }
            if (!empty($back_image)) {
              $bunlink = 'assets/images/' . $back_image;
              unlink($bunlink);
            }
            $front_image = '';
            $back_image = '';
            $data['code'] = 200;
            $data['msg'] = 'Product Unsync Successfully';
          }else {
            $data['code'] = 100;
            $data['msg'] = 'Something went wrong';
          }
        }
        echo json_send($data);
      } else {
          $this->load->load_admin('errors/shop-errors/shop-not-found');
      }
    }
}
