<table id="product-list" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Title</th>
            <th>Front Image</th>
            <th>Back Image</th>
            <th>Colors</th>
            <th>Sizes</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th>Title</th>
            <th>Front Image</th>
            <th>Back Image</th>
            <th>Colors</th>
            <th>Sizes</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    </tfoot>
</table>
<div class="modal" id="delete" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
            <h4 class="modal-title custom_align" id="Heading">Delete this Category</h4>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Are you sure you want to delete this product?</div>
          </div>
          <div class="modal-footer ">

            <button type="button" data-id="" data-table="<?=$table;?>" style="display:flex;float:right" class="btn btn-success delete-btn"><i class="fa fa-check"></i> Yes<img height=20 width=20 id="loaderId" style="display:none" src="<?=base_url('assets/images/loadingimage.gif'); ?>" alt=""></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
          </div>
      </div>
  </div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    var interval = setInterval(doStuff, 2000);
    $('.loading,.loading progress').show();
    function doStuff(){
      if(window.sessionToken){
          $('.loading,.loading progress').hide();
          var table = $('#product-list').DataTable({
            "serverSide": true,
            "ordering": false,
            "scrollX": true,
            "ajax":{
              "url": '<?=base_url(); ?>Home/product_list?shop=<?=$shop.'&shop_id='.$shop_id; ?>',
              "type": "POST",
              "complete": function(data) {
                var respose = JSON.parse(data.responseText);
                if(respose.code && respose.code == 100){
                    window.ShowErrorToast(respose.msg);
                    window.GenerateSessionToken();
                    table.ajax.reload();
                }
              },
            },
            "initComplete":function(i){
              $('.delete-product').click(function() {
                $('.delete-btn').attr('data-id',$(this).data('id'));
              });
            }
          });
        clearInterval(interval);
      }
    }

  });
</script>
