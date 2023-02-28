<?php
  $shop       = $_GET['shop'];
  $shop_id    = shop_id($shop);
  $get_product_count = $this->db->query("select product_count from products where shop_id='".$shop_id."' order by id desc limit 1")->result();
  $get_shop_details = $this->db->query("select * from shopify_stores where id='".$shop_id."' and shop = '".$shop."' ")->result();

  if (empty($get_product_count)) {
    $get_product_count1 = 0;
  }else {
    $get_product_count1 = $get_product_count[0]->product_count;
  }

  $planId = $get_shop_details[0]->plan_id;
  $chargeId = $get_shop_details[0]->charge_id;
  if ( $planId == 0 && $get_product_count1 >= 1 ) { ?>
    <div class="alert alert-warning">
      <strong>Warning!</strong> You reached the maximum product limit <a href="<?php echo base_url(); ?>Home/shopPlans?shop=<?php echo $shop; ?>">click here</a> to get bigger plan.
    </div>
<?php exit; }
  if ( $planId == 1 && $get_product_count1 >= 50 ) { ?>
    <div class="alert alert-warning">
      <strong>Warning!</strong> You reached the maximum product limit <a href="<?php echo base_url(); ?>Home/shopPlans?shop=<?php echo $shop; ?>">click here</a> to get bigger plan.
    </div>
<?php exit; }
if ( $planId == 2 && $get_product_count1 >= 200 ) { ?>
  <div class="alert alert-warning">
    <strong>Warning!</strong> You reached the maximum product limit <a href="<?php echo base_url(); ?>Home/shopPlans?shop=<?php echo $shop; ?>">click here</a> to get bigger plan.
  </div>
<?php exit; }
if ( $planId == 3 ) { }?>




<?php
  // if ($planId == 0 && $get_product_count1 < 10) {
  //   // $viewBlock = TRUE;
  // }
//  if ($planId == 0 && $get_product_count1 >= 10) {
    // $viewBlock = FALSE;
?>
<?php
//  }
  // if ($chargeId && $planId == 1 ) {
  //   // $viewBlock = TRUE;
  //
  // }
  // if ($viewBlock) {
?>
<div class="container register-form col-md-8 col-md-offset-2 hideDetails">
    <form data-toggle="validator" role="form" class="create-form">
      <input type="hidden" class="form-control" name="planid" value="<?php echo $planId; ?>"/>
      <input type="hidden" class="form-control" name="productCount" value="<?php echo $get_product_count1; ?>"/>
        <h3>Create Product</h3>
        <div class="form-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Title</label>
                        <input type="text" class="form-control" placeholder="Product Title *" name="product_title" value="" required="required" />
                    </div>
                </div>
                <?php if ($planId == 3): ?>
                <div class="col-md-12">
                  <div class="form-group">
                    <!-- <label class="control-label" for="cust_option">Customization</label>
                    <select class="cust_option form-control"  id="cust_option" name="cust_option">
                      <option value="1" selected>Single Side</option>
                      <option value="2">Both Side</option>
                    </select> -->
                  </div>
                </div>
              <?php endif; ?>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label" for="images">Images</label>
                    <select class="custom-select form-control"  id="images" name="images" required>
                      <option value="front" selected>Front</option>
                      <option value="frontBack">Front + Back</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6 imageView">
                    <div class="form-group">
                        <div class="img-box drop_box">
                            <?=image_asset('no-image.png', '', array('height' => '100%', 'alt' => 'Front Image'))?>
                        </div>
                        <input type="file" name="front_image" class="file" required="required">
                        <div class="input-group col-xs-12">
                          <span class="input-group-addon"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                          <input type="text" class="form-control" disabled placeholder="Upload Front Img" required="required">
                          <span class="input-group-btn">
                            <button class="browse btn btn-primary" type="button">
                              <i class="fa fa-search" aria-hidden="true"></i> Browse
                            </button>
                          </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 imageView" style="display:none" id="back_image_view">
                    <div class="form-group">
                        <div class="img-box drop_box">
                            <?=image_asset('no-image.png', '', array('height' => '100%', 'alt' => 'Front Image'))?>
                        </div>
                        <input type="file" name="back_image" class="file fileValue">
                        <div class="input-group col-xs-12">
                          <span class="input-group-addon"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                          <input type="text" class="form-control" disabled placeholder="Upload Front Img" required="required">
                          <span class="input-group-btn">
                            <button class="browse btn btn-primary" type="button">
                              <i class="fa fa-search" aria-hidden="true"></i> Browse
                            </button>
                          </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Description</label>
                        <textarea name="description" style="width: 100%" class="form-control" placeholder="Description..." rows="6"></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group text-left">
                     <label class="control-label">Product Colours</label>
                     <div class="input_fields_wrap">
                        <div class="color-box" style="position: relative;">
                           <input type="color" class="color-input" name="productcolor[]" value="#ff0000">
                           <!-- <div class="radio-btn">
                              <label for="t-option">
                                 <input type="radio" id="t-option" data-color="#ff0000" name="selected_color" required="required">
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </label>
                           </div> -->
                        </div>
                     </div>
                     <div style="clear: both;">
                        <a href="#" class="btn btn-primary btn-primary add_field_button">
                            <span class="glyphicon glyphicon-plus"></span> Add More Colors
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-md-12">
                   <div class="form-group">
                       <label class="control-label">Enter the sizes in comma separated format.</label>
                       <div class="size-array">
                           <input class="form-control"  name="size_array" placeholder="XS,S,L,M" required="required"/>
                       </div>
                   </div>
               </div>
               <div class="col-md-12" id="errors"></div>
               <div class="col-md-12">
                 <div class="form-group">
                     <label class="control-label">Enter Price</label>
                     <div class="size-array">
                         <input type="number" step='any' onkeypress="return isNumberKey(event)" class="form-control"  name="product_price" placeholder="" required="required"/>
                     </div>
                 </div>
               </div>
               <div class="col-md-12">
                   <div class="form-group">
                       <button type="submit" class="btnSubmit btn btn-primary">Submit</button>
                   </div>
               </div>
        </div>
    </form>
</div>
<?php //} ?>
<script>
function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
          && (charCode < 48 || charCode > 57))
           return false;
        return true;
     }
</script>
<style>
.register-form .img-box {
  height: 215px;
  width: 215px;
}
.register-form .img-box img{
    max-width: 240px;
    height: 100%;
    background: #bfbfbf;
}
.register-form .input-group[class*="col-"]{
  width: 70%;
}
.register-form .input-group-addon{
  padding:6px;
}
.register-form .input-group-addon i{
  font-size:12px;
}
.register-form .input-group .form-control::placeholder{
  font-size: 10px;
}
.register-form .input-group .btn {
    padding: 6px 3px;
		font-size: 12px;
		line-height: 20px;
}
.register-form .form-control{
  padding: 6px 3px;
}
/* -----------Media Query------------- */
@media only screen and (min-width: 320px) and (max-width: 556px){
  .register-form .img-box {
    height: auto;
    width: 100%;
  }
	.register-form .input-group[class*="col-"]{
	  width: 100%;
	}
	.register-form .input-group-addon{
	  padding:6px 12px;
	}
	.register-form .input-group-addon i{
	  font-size:14px;
	}
	.register-form .input-group .form-control::placeholder{
	  font-size: 14px;
	}
	.register-form .input-group .btn {
	    padding: 6px 10px;
			font-size: 14px;
			line-height: 20px;
	}
	.register-form .form-control{
	  padding: 6px 6px;
	}
  .input_fields_wrap div button{
    right: 0;
  }
}
@media only screen and (min-width: 557px) and (max-width: 991px){
  .register-form .img-box {
    height: 250px;
    width: 250px;
  }
	.register-form .input-group[class*="col-"]{
	  width: 50%;
	}
  .input_fields_wrap div button{
    right: 15px;
  }
}
</style>
