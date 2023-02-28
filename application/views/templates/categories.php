<div class="row">
	<div class="col-md-4">
		<h3>Clip Art Categories</h3>
		<form class="create-cat" role="form">
			<div class="form-group">
				<label for="cat_name">Name</label>
				<input type="text" class="form-control" id="cat_name" placeholder="Category Name" name="cat_name" required>
			</div>
			<div class="form-group">
				<label for="parent_cat">Parent Category</label>
				<select class="form-control" id="parent_cat" name="parent_cat" required>
					<option value="0">None</option>
					<?php if(count($categories) > 0): ?>
						<?php foreach($categories as $category): ?>
							<option value="<?=$category->id?>"><?=$category->cat_name?></option>
						<?php endforeach ?>
					<?php endif ?>
				</select>
			</div>
			<div class="form-group">
			    <div class="drop_box img-box">
			        <?=image_asset('no-image.png', '', array('width' => '100%','height'=>'100%', 'alt' => 'Front Image'))?>
			    </div>
			    <input type="file" name="cat_image" class="file">
			    <div class="input-group col-xs-12">
			      <span class="input-group-addon"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
			      <input type="text" class="form-control" disabled placeholder="Upload Image">
			      <span class="input-group-btn">
			        <button class="browse btn btn-primary" type="button"><i class="fa fa-search" aria-hidden="true"></i> Browse</button>
			      </span>
			    </div>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Submit">
			</div>
		</form>
	</div>
	<div class="col-md-8">
		<table id="category-list" class="display" style="width:100%">
				<thead>
						<tr>
							<th>Category Image</th>
							<th>Title</th>
							<th>Parent</th>
							<th>Count</th>
							<th>Action</th>
						</tr>
				</thead>

				<tfoot>
						<tr>
							<th>Category Image</th>
							<th>Title</th>
							<th>Parent</th>
							<th>Count</th>
							<th>Action</th>
						</tr>
				</tfoot>
		</table>
	</div>
</div>
<!-- <div class="modal" id="delete" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
            <h4 class="modal-title custom_align" id="Heading">Delete this Ctegory</h4>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Are you sure you want to delete this Category?</div>
          </div>
          <div class="modal-footer ">
            <button type="button" data-id="" data-table="<?=$table;?>" class="btn btn-success delete-btn"><i class="fa fa-check"></i> Yes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
          </div>
      </div>
  </div>
</div> -->

<div class="modal" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
            <h4 class="modal-title custom_align" id="Heading">Edit Category</h4>
          </div>
          <div class="modal-body">
            <form class="edit-cat" role="form">
        			<div class="form-group">
        				<label for="cat_name">Name</label>
        				<input type="text" class="form-control" placeholder="Animals" name="cat_name">
        			</div>
        			<div class="form-group">
        				<label for="parent_cat">Parent Category</label>
        				<select class="form-control"  name="parent_cat">
        					<option value="0">None</option>
        					<?php if(count($categories) > 0): ?>
        						<?php foreach($categories as $category): ?>
        							<option value="<?=$category->id?>"><?=$category->cat_name?></option>
        						<?php endforeach ?>
        					<?php endif ?>
        				</select>
        			</div>
        			<div class="form-group">
        			    <div class="drop_box img-box">
        			        <?=image_asset('no-image.png', '', array('width' => '100%','height'=>'100%', 'alt' => 'Category Image'))?>
        			    </div>
        			    <input type="file" name="cat_image" class="file">
        			    <div class="input-group col-xs-12">
        			      <span class="input-group-addon"><i class="fa fa-picture-o" aria-hidden="true"></i></span>
        			      <input type="text" class="form-control" disabled placeholder="Upload Image">
        			      <span class="input-group-btn">
        			        <button class="browse btn btn-primary" type="button"><i class="fa fa-search" aria-hidden="true"></i> Browse</button>
        			      </span>
        			    </div>
        			</div>
        		</form>
          </div>
          <div class="modal-footer ">
            <button type="button" data-id="" data-table="<?=$table;?>" class="btn btn-success edit-cat-btn"><i class="fa fa-check"></i> Update</button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          </div>
      </div>
  </div>
</div>
<style>
.create-cat .drop_box {
  height: 215px;
  width: 215px;
}
.create-cat .drop_box img{
    max-width: 240px;
    height: 100%;
    background: #bfbfbf;
}
.create-cat .input-group[class*="col-"]{
  width: 70%;
}
.create-cat .input-group-addon{
  padding:6px;
}
.create-cat .input-group-addon i{
  font-size:12px;
}
.create-cat .input-group .form-control::placeholder{
  font-size: 10px;
}
.create-cat .input-group .btn {
    padding: 6px 3px;
		font-size: 12px;
		line-height: 20px;
}
.create-cat .form-control{
  padding: 6px 3px;
}
/* -----------Media Query------------- */
@media only screen and (min-width: 320px) and (max-width: 556px){
  .create-cat .drop_box {
    height: auto;
    width: 100%;
  }
	.create-cat .input-group[class*="col-"]{
	  width: 100%;
	}
	.create-cat .input-group-addon{
	  padding:6px 12px;
	}
	.create-cat .input-group-addon i{
	  font-size:14px;
	}
	.create-cat .input-group .form-control::placeholder{
	  font-size: 14px;
	}
	.create-cat .input-group .btn {
	    padding: 6px 10px;
			font-size: 14px;
			line-height: 20px;
	}
	.create-cat .form-control{
	  padding: 6px 6px;
	}
}
@media only screen and (min-width: 557px) and (max-width: 991px){
  .create-cat .drop_box {
    height: 250px;
    width: 250px;
  }
	.create-cat .input-group[class*="col-"]{
	  width: 50%;
	}
}
</style>

<script>
jQuery(document).ready(function($) {
	var interval = setInterval(doStuff, 2000);
	$('.loading,.loading progress').show();
	function doStuff(){
		if(window.sessionToken){
				$('.loading,.loading progress').hide();
				clearInterval(interval);
				window.CreateCategoryTable();
		}
	}

});
</script>
