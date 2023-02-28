<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

class Customizer extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Global_model');
        $this->load->library('form_validation');
    }



    public function Customizer()
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
                                                <?php
                                                $fonts = [" Anton"," Arimo"," Arvo"," Bowlby One SC"," Brawler"," Cabin"," Crimson Text"," Cuprum"," Dancing Script"," Droid Sans"," Droid Sans Mono"," Droid Serif"," EB Garamond"," Francois One"," Goudy Bookletter 1911"," Gruppo"," Inconsolata"," Indie Flower"," Josefin Sans"," Lobster"," Lora"," Maven Pro"," Merriweather"," Muli"," Nunito"," Open Sans"," Open Sans Condensed"," Oswald"," Pacifico"," Play"," Playfair Display"," PT Sans"," PT Sans Narrow"," PT Serif"," Raleway"," Shadows Into Light"," Ubuntu"," Varela Round"," Vollkorn"," Walter Turncoat"," Yanone Kaffeesatz"];
                                                foreach ($fonts as $ft) {
                                                  $ar=implode('+',array_filter(explode(' ',$ft)));
                                                  ?>
                                                  <li role="presentation" style="font-family: <?=$ft?>; font-weight: 400">
                                                  <label for="<?=$ar?>">
                                                  <span class="font_style" font_style="<?=$ar?>">
                                                      <?=$ft?>
                                                  </span>
                                                  </label>
                                                  </li>
                                                  <?php
                                                }
                                                ?>
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
                          Page URL
                            Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui minim occaecat veniam. Fugiat veniam incididunt anim aliqua enim pariatur veniam sunt est aute sit dolor anim. Velit non irure adipisicing aliqua ullamco irure incididunt irure non esse consectetur nostrud minim non minim occaecat. Amet duis do nisi duis veniam non est eiusmod tempor incididunt tempor dolor ipsum in qui sit. Exercitation mollit sit culpa nisi culpa non adipisicing reprehenderit do dolore. Duis reprehenderit occaecat anim ullamco ad duis occaecat ex.

                      </div>
                    </div>
                    <div>
                       <center>
                         <div style="padding:15px">
                           <input type="checkbox" class="checkbox_click">
                           <label style="padding-left: 8px;">Are you done customization?</label>
                           <br>
                         </div>
                         <p class="pageURL">{{ page.url }}</p><br>
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
                    <form method="post" action="/account/login" id="customer_login" accept-charset="UTF-8">
                      <input type="hidden" name="form_type" value="customer_login">
                      <input type="hidden" name="utf8" value="✓">
                      <input type="hidden" name="return_to"  value="/pages/create-your-own">
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

          <script>
          var appUrl = "<?=base_url()?>";

          // $scriptSource = 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js';
          // if(typeof jQuery=='undefined') {
          //     var headTag = document.getElementsByTagName("head")[0];
          //     var jqTag = document.createElement('script');
          //     jqTag.type = 'text/javascript';
          //     jqTag.src = $scriptSource;
          //     headTag.appendChild(jqTag);
          // }
          </script> 
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/html2canvas.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/bootstrap-proper-min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/bootstrap-min.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/fabric.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/tui-editor.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/file-saver.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/color-picker.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/sweet-alert.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/customize-control.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/drop-zone.js"></script>
        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/base64.js"></script>

        <script type="text/javascript" src="<?=base_url()?>assets/customizer/js/customizer.js"></script>
       <?php
       $html = ob_get_clean();
       return $this->output->set_content_type('application/liquid')->set_status_header(200)->set_output($html);

    }

}
