<div class="container register-form col-md-8 col-md-offset-2">

    <form  role="form" class="edit-form" enctype="multipart/form-data" action="<?=CONTROLLER?>/Home/create_product">
        <h3>Edit  Product</h3>
        <div class="form-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label">Title</label>
                      <input type="text" class="form-control" placeholder="Product Title *" name="product_title" value="<?=$products->title?>" required="required" />
                    </div>
                </div>
                <?php
                    $get_shop_details = $this->db->query("select * from shopify_stores where id='".$shop_id."' and shop = '".$shop."' ")->result();
                    $planId = $get_shop_details[0]->plan_id;
                    if ($planId == 3): ?>
                <div class="col-md-12">
                  <div class="form-group">
                    <!-- <label class="control-label" for="cust_option">Customization</label>
                    <select class="cust_option-select form-control"  id="cust_option" name="cust_option">
                      <option value="1" <?php if($products->cust_option == 1){echo "selected";} ?>>Single Side</option>
                      <option value="2" <?php if($products->cust_option == 2){echo "selected";} ?>>Both Side</option>
                    </select> -->
                  </div>
                </div>
              <?php endif; ?>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label" for="images">Choose Image Type</label>
                    <select class="custom-select form-control"  id="EditImages" name="editimages" required>
                      <option value="front">Front</option>
                      <option value="frontBack" <?php if($products->back_image) { echo "selected"; } ?>>Front + Back</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <label class="control-label">Images</label>
                </div>

                <div class="col-md-6 imageView">
                    <div class="form-group">
                        <div class="img-box">
                          <img src="<?=image_thumb( $products->front_image, 240,280 )?>" alt="Front Image">
                        </div>
                        <input type="file" name="front_image" class="file">
                        <div class="input-group col-xs-12">
                          <span class="input-group-addon"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                          <input type="text" class="form-control" disabled placeholder="Change Front Image">
                          <span class="input-group-btn">
                            <button class="browse btn btn-primary" type="button"><i class="fa fa-search" aria-hidden="true"></i> Change</button>
                          </span>
                        </div>
                    </div>
                </div>

                <div id="back_image_view" class="col-md-6 imageView" <?php if($products->back_image) { echo "style='display:block;'"; } else { echo "style='display:none;'"; } ?>>
                    <div class="form-group">
                        <div class="img-box">
                          <img src="<?=image_thumb( $products->back_image, 240,280 )?>" alt="Back Image">
                        </div>
                        <input type="file" name="back_image" class="file">
                        <div class="input-group col-xs-12">
                          <span class="input-group-addon"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                          <input type="text" class="form-control" disabled placeholder="Change Back Image">
                          <span class="input-group-btn">
                            <button class="browse btn btn-primary" type="button"><i class="fa fa-search" aria-hidden="true"></i> Change</button>
                          </span>
                        </div>
                      </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Description</label>
                        <textarea name="description" style="width: 100%" class="form-control" placeholder="Description..." rows="6"><?=$products->description?></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group text-left">
                     <label class="control-label">Product Colours</label>
                     <div class="input_fields_wrap">
                        <?php $colors = explode(',',$products->color_ids); ?>
                        <?php foreach($colors as $color): ?>
                        <div class="color-box" style="position: relative;">
                           <input type="color" class="color-input" name="productcolor[]" value="<?=$color?>">
                           <button class="btn btn-danger remove_field"><span class="fa fa-trash"></span></button>
                           <!-- <div class="radio-btn">
                              <label for="t-option">
                                 <input type="radio" id="t-option" data-color="#ff0000" name="selected_color" required="required">
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </label>
                           </div> -->
                        </div>
                      <?php endforeach ?>
                     </div>
                     <div style="clear: both;">
                        <a href="#" class="btn btn-primary btn-primary add_field_button">
                            <span class="glyphicon glyphicon-plus"></span> Add More Colors
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-md-12">
                  <?php $sizes = explode(',',$products->size_ids); ?>
                   <div class="form-group">
                       <label class="control-label">Enter the sizes in comma separated format.</label>
                       <div class="size-array">
                           <input class="form-control"  name="size_array" placeholder="" value="<?=implode(',', $sizes)?>" required="required"/>
                       </div>
                   </div>
               </div>
               <div class="col-md-12" id="errors"></div>
               <div class="col-md-12">
                 <div class="form-group">
                     <label class="control-label">Enter Price</label>
                     <div class="size-array">
                         <input type="number" step='any' onkeypress="return isNumberKey(event)" class="form-control"  name="product_price" placeholder="" value="<?= $products->product_price ?>" required="required"/>
                     </div>
                 </div>
               </div>
               <div class="col-md-12">
                   <div class="form-group">
                      <input type="hidden" name="product_id" value="<?=$product_id?>">
                      <input type="hidden" name="productId" value="<?=$products->product_id?>">
                      <a href="<?=base_url('Home/Dashboard?shop='.$shop);?>" class="btn btn-primary back_pro">Back to products</a>
                      <button type="submit" class="btnSubmit btn btn-primary">Submit</button>
                   </div>
               </div>
        </div>
    </form>
</div>
<script>
function isNumberKey(evt)
     {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
          && (charCode < 48 || charCode > 57))
           return false;

        return true;
     }
</script>
