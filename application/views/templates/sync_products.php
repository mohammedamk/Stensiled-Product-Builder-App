<style media="screen">
.sync-box{
  padding: 4px;
  margin-top: -18px;
  position: fixed;
  right: 0;
  background: white;
  width: 100%;
  box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
}
</style>
<div class="sync-box">
  <div class="" style="float:right">
    <button type="button" class="btn btn-primary sync_all" name="button">Sync All</button>
    <button type="button" class="btn btn-primary async_all" name="button">Unsync All</button>
  </div>
</div>
<table id="product-data" class="display table table-bordered table-striped table-hover" style="width:100%;margin-top: 50px;">
    <thead>
        <tr>
            <th style="width: 10%;"><input style="margin-right: 5px;" type="checkbox" id="checkAll">Action</th>
            <th>Sync/Unsync</th>
            <th>Product Name</th>
            <th>Front Image</th>
            <th>Back Image</th>
            <th>Colors</th>
            <th>Sizes</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
      <?php $count = 0; foreach ($product_data as $product_data) {

          $colorArray = array();
          $sizeArray = array();
          $implodeColor = array();
          $implodeSize = array();
          if(count($product_data['options']) >= 1){
            foreach ($product_data['options'] as $options) {
              if ($options['name'] == 'Color') {
                $colorArray[] = $options['values'];
              }
              if ($options['name'] == 'Size') {
                $sizeArray[] = $options['values'];
              }
            }
          }
          if ($colorArray) {$implodeColor[] = implode(',',$colorArray[0]); }
          if ($sizeArray) {$implodeSize[] = implode(',',$sizeArray[0]); }
          // $product_data['body_html'];
          // exit;
          // echo "<pre>";
          // print_r($implodeSize);
          // print_r($sizeArray);
          $get_product_id = $this->db->query('select * from products where product_id="'.$product_data['id'].'"')->result();
          if (count($get_product_id) > 0) {
            $checked = "checked";
            $btnAction = '<button type="button" class="get_tr_count btn btn-info async_selected" data-count="'.$count.'" name="button">Unsync Product</button><div class="btnCount'.$count.'"></div>';
          }else {
            $btnAction = '<button type="button" class="get_tr_count btn btn-info sync_selected" data-count="'.$count.'" name="button">Sync Product</button><div class="btnCount'.$count.'"></div>';
            $checked = '';
          }
        ?>

        <tr>
          <td> <input type="checkbox" <?php echo $checked; ?> class="check_selected chkbox<?php echo $count; ?>" name="checkval[]" value=""> </td>
          <td style="display:none"><?php echo $product_data['id']; ?></td>
          <td style="display:none"><?php echo $product_data['handle']; ?></td>
          <td style="display:none"><?php echo $product_data['variants'][0]['id']; ?></td>
          <td style="display:none"><?php echo $product_data['body_html']; ?></td>
          <td style="display:none"><?php if($implodeColor){echo $implodeColor[0];}else{echo "";}  ?></td>
          <td style="display:none"><?php if($implodeSize){echo $implodeSize[0];}else{echo "";}  ?></td>
          <td style="display:none"><?php if(count($product_data['images']) >0){ ?><?php echo $product_data['images'][0]['src']; ?><?php }?></td>
          <td style="display:none"><?php if(count($product_data['images']) >1){ ?><?php echo $product_data['images'][1]['src']; ?><?php }?></td>
          <td><?php echo $btnAction; ?></td>
          <td><?php echo $product_data['title'] ?></td>
          <td> <?php if(count($product_data['images']) >0){ ?><img height="50" width="50" src="<?php echo $product_data['images'][0]['src']; ?>" alt="Back Image"><?php }else{echo "Front Image NA";} ?> </td>
          <td> <?php if(count($product_data['images']) >1){ ?><img height="50" width="50" src="<?php echo $product_data['images'][1]['src']; ?>" alt="Back Image"><?php }else{echo "Back Image NA";} ?> </td>
          <td>
            <?php
              if ($colorArray) {
                foreach ($colorArray[0] as  $color) {
                  echo '<div class="color swatch-box" style="background:'.$color.'"></div>';
                }
              }else {
                echo "Color NA";
              }
            ?>
          </td>
          <td><?php
            if ($sizeArray) {
              foreach ($sizeArray[0] as  $size) {
                echo '<div class="size swatch-box">'.$size.'</div>';
              }
            }else {
              echo "Size NA";
            }
          ?>
          </td>
          <td><?php echo $product_data['variants'][0]['price']; ?></td>
        </tr>
      <?php $count++; } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Action</th>
            <th>Product Name</th>
            <th>Front Image</th>
            <th>Back Image</th>
            <th>Colors</th>
            <th>Sizes</th>
            <th>Price</th>
        </tr>
    </tfoot>
</table>
<script type="text/javascript">
  $(document).ready(function(){
    //Check All
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    //Async Product
    $('.async_selected').click(function(){
      $('.loading,.loading progress').show();
      var count = $(this).data('count');
      var values = new Array();
      var data = $("input[name='checkval[]']").parents('tr:eq('+count+')');
      values.push({ 'product_id':$(data).find('td:eq(1)').text(), 'pro_handle':$(data).find('td:eq(2)').text() , 'variant_id':$(data).find('td:eq(3)').text(), 'description':$(data).find('td:eq(4)').text(), 'color':$(data).find('td:eq(5)').text(), 'size':$(data).find('td:eq(6)').text(), 'front_image':$(data).find('td:eq(7)').text(), 'back_image':$(data).find('td:eq(8)').text(), 'title':$(data).find('td:eq(10)').text(), 'price':$(data).find('td:eq(15)').text()});
      AsyncProduct(values);
    });
    $('.async_all').click(function(){
      $('.loading,.loading progress').show();
      var values = new Array();
      $.each($("input[name='checkval[]']:checked"), function() {
        var data = $(this).parents('tr:eq(0)');
        values.push({ 'product_id':$(data).find('td:eq(1)').text(), 'pro_handle':$(data).find('td:eq(2)').text() , 'variant_id':$(data).find('td:eq(3)').text(), 'description':$(data).find('td:eq(4)').text(), 'color':$(data).find('td:eq(5)').text(), 'size':$(data).find('td:eq(6)').text(), 'front_image':$(data).find('td:eq(7)').text(), 'back_image':$(data).find('td:eq(8)').text(), 'title':$(data).find('td:eq(10)').text(), 'price':$(data).find('td:eq(15)').text()});
      });
      AsyncProduct(values)
    })
    //Sync Product
    $('.sync_selected').click(function(){
        $('.loading,.loading progress').show();
      var count = $(this).data('count');
      var values = new Array();
      var data = $("input[name='checkval[]']").parents('tr:eq('+count+')');
      values.push({ 'product_id':$(data).find('td:eq(1)').text(), 'pro_handle':$(data).find('td:eq(2)').text() , 'variant_id':$(data).find('td:eq(3)').text(), 'description':$(data).find('td:eq(4)').text(), 'color':$(data).find('td:eq(5)').text(), 'size':$(data).find('td:eq(6)').text(), 'front_image':$(data).find('td:eq(7)').text(), 'back_image':$(data).find('td:eq(8)').text(), 'title':$(data).find('td:eq(10)').text(), 'price':$(data).find('td:eq(15)').text()});
      SyncProduct(values);
    });
    $('.sync_all').click(function(){
      $('.loading,.loading progress').show();
      var values = new Array();
      $.each($("input[name='checkval[]']:checked"), function() {
        var data = $(this).parents('tr:eq(0)');
        values.push({ 'product_id':$(data).find('td:eq(1)').text(), 'pro_handle':$(data).find('td:eq(2)').text() , 'variant_id':$(data).find('td:eq(3)').text(), 'description':$(data).find('td:eq(4)').text(), 'color':$(data).find('td:eq(5)').text(), 'size':$(data).find('td:eq(6)').text(), 'front_image':$(data).find('td:eq(7)').text(), 'back_image':$(data).find('td:eq(8)').text(), 'title':$(data).find('td:eq(10)').text(), 'price':$(data).find('td:eq(15)').text()});
      });
      SyncProduct(values)
    })
    function SyncProduct(arraydata) {
      $.ajax({
        url:base_url('Home/InsertSyncProduct?shop=')+'<?php echo $shop; ?>',
        type:'POST',
        data:{selected_array:arraydata},
        success:function(response) {
          // $('.loading,.loading progress').hide();
          location.reload();
          var parseData = JSON.parse(response);
          ShopifyApp.flashNotice(parseData.msg);
        }
      });
    }
    function AsyncProduct(arraydata) {
      $.ajax({
        url:base_url('Home/asyncProduct?shop=')+'<?php echo $shop; ?>',
        type:'POST',
        data:{selected_array:arraydata},
        success:function(response) {
          // $('.loading,.loading progress').hide();
          location.reload();
          var parseData = JSON.parse(response);
          ShopifyApp.flashNotice(parseData.msg);
        }
      });
    }
  });
</script>
