<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Stensiled Product Builder</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php echo css_asset('jquery.dataTables.css'); ?>
        <?php echo css_asset('dropzone.css'); ?>
        <?php echo css_asset('fontawesome.min.css'); ?>
        <?php echo css_asset('bootstrap.min.css'); ?>
        <?php echo css_asset('app.css'); ?>
        <?php echo css_asset('jquery.fancybox.css'); ?>

        <?php echo js_asset('jquery.js'); ?>
        <?php echo js_asset('jquery-migrate.min.js'); ?>
        <?php echo js_asset('dropzone.js'); ?>
        <?php echo js_asset('app.js'); ?>
        <?php echo js_asset('sweetalert2.js'); ?>
        <?php echo js_asset('jquery.dataTables.js'); ?>
        <?php echo js_asset('bootstrap.min.js'); ?>
        <?php echo js_asset('jquery.fancybox.pack.js'); ?>
        <?php echo js_asset('lazyload.js'); ?>

        <!-- <script src="https://cdn.shopify.com/s/assets/external/app.js"></script> -->
        <script src="https:////cdnjs.cloudflare.com/ajax/libs/headjs/1.0.3/head.load.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            // ShopifyApp.init({
            //     apiKey : '<?php echo $this->config->item('shopify_api_key'); ?>',
            //     shopOrigin : '<?php echo 'https://'.$_GET['shop'];?>'
            // });

            var _configs = {
                apiKey: '<?=$this->config->item('shopify_api_key'); ?>',
                shop: '<?=$shop?>',
            }
            <?php
            if(isset($host)){
              ?>
              _configs['host']='<?=$host?>';
            <?php }
            ?>
            function base_url(path) {
                var base_url = '<?=base_url(); ?>';
                if (path != '') {
                    return base_url+path;
                }else{
                    return base_url;
                }
            }
            var shop = '<?=$_GET['shop'];?>';
            var shop_id = '<?=shop_id($_GET['shop']);?>';
            var CONTROLLER = '<?=CONTROLLER;?>';
        </script>
        <script type="text/javascript">

        window.GenerateSessionToken = function(){
          var AppBridgeUtils = window['app-bridge-utils'];
          const sessionToken = AppBridgeUtils.getSessionToken(window.app);
          sessionToken.then(function(result) {
            $.ajaxSetup({
              headers: { "Authorization": "Bearer " + result }
            });
            window.sessionToken = result;
          }, function(err) {
              // console.log(err); // Error: "It broke"
          });
        }

        window.ShowErrorToast = function(msg){
          var Toast = window.ShopifyApp.Toast;
          const toastError = Toast.create(window.ShopifyApp.App, {message: msg,duration: 5000,isError: true});
          toastError.dispatch(Toast.Action.SHOW);
        }
        window.ShowSuccesToastToast = function(msg){
          var Toast = window.ShopifyApp.Toast;
          const toastError = Toast.create(window.ShopifyApp.App, {message: msg,duration: 5000});
          toastError.dispatch(Toast.Action.SHOW);
        }

        head.ready("shopifyAppBridgeUtils", function() {
            var shopName = _configs.shop;
            var token = '';
            function initializeApp() {
              var app = createApp({
                apiKey:_configs.apiKey,
                shopOrigin: shopName
              });
              window.app = app;
              window.GenerateSessionToken();
              return app;
            }

            var AppBridge = window['app-bridge'];
            var AppBridgeUtils = window['app-bridge-utils'];
            var createApp = AppBridge.default;
            var actions = AppBridge.actions;
            window.ShopifyApp = {
              App: initializeApp(),
              ShopOrigin: _configs.shop,
              ResourcePicker: actions.ResourcePicker,
              Toast: actions.Toast,
              Button: actions.Button,
              TitleBar: actions.TitleBar,
              Modal: actions.Modal,
              Redirect: actions.Redirect,
              Loading: actions.Loading,
            };
            var ShopifyApp = window.ShopifyApp;
          });
        </script>
        <script type="text/javascript">
          head.load({shopifyAppBridge: "https://unpkg.com/@shopify/app-bridge@1.30"},{shopifyAppBridgeUtils: "https:////unpkg.com/@shopify/app-bridge-utils@1.30"});
        </script>

        <?php $shop = $_GET['shop']; ?>
        <!-- <?php $access_token=$this->session->userdata('access_token');?> -->
    </head>
    <body>
    <div id="wrapper">
      <div class="loading">
          <div class="loader">
              <div class="finger finger-1">
                  <div class="finger-item">
                      <span></span><i></i>
                  </div>
              </div>
              <div class="finger finger-2">
                  <div class="finger-item">
                      <span></span><i></i>
                  </div>
              </div>
              <div class="finger finger-3">
                  <div class="finger-item">
                      <span></span><i></i>
                  </div>
              </div>
              <div class="finger finger-4">
                  <div class="finger-item">
                      <span></span><i></i>
                  </div>
              </div>
              <div class="last-finger">
                  <div class="last-finger-item"><i></i></div>
              </div>
              <progress min="0" max="100" value="0">0% complete</progress>
          </div>

      </div>
      <input type="hidden" id="base" value="<?=base_url(); ?>">
        <!--Navbar-->
        <nav class="navbar navbar-inverse bg-dark">
          <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- <a class="navbar-brand" href="<?=base_url('Home/Dashboard?shop='.$shop);?>"></a> -->
            </div>

            <div class="navbar-header collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active clip_art"><a href="<?=base_url('Home/Dashboard?shop='.$shop);?>">Products</a></li>
                    <li class="clip_art"><a href="<?=base_url('Home/CreateProductView?shop='.$shop);?>">Create Product</a></li>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown">Clip Arts <span class="caret"> </span>
                        </a>
                        <ul class="dropdown-menu mq-dropdown">
                            <!-- <li><a href="<?=base_url('Home/AddClipArtView?shop='.$shop);?>">Add Clip Arts</a></li> -->
                            <li><a href="<?=base_url('Home/ClipArtView?shop='.$shop);?>">Clip Art Categories</a></li>
                            <li><a href="<?=base_url('Home/cliparts?shop='.$shop);?>">Clip Arts</a></li>
                        </ul>
                    </li>
                    <?php
                      $shop_id    = shop_id($shop);
                      $get_shop_details = $this->db->query("select * from shopify_stores where id='".$shop_id."' and shop = '".$shop."' ")->result();
                      $planId = $get_shop_details[0]->plan_id;
                        if ($planId == 2 || $planId == 3) {?>
                          <li class="clip_art"><a href="<?=base_url('Home/SyncProducts?shop='.$shop);?>">Sync Products</a></li>
                    <?php  }  ?>
                     <li class="clip_art"><a href="<?=base_url('Home/shopPlans?shop='.$shop);?>">Plans</a></li>
                    <!-- <li class="clip_art"><a href="<?=base_url('Home/installInstruct?shop='.$shop);?>">Instructions</a></li> -->
                </ul>
            </div>
        </nav>

        <!-- widgets -->
        <div id="app-container">
