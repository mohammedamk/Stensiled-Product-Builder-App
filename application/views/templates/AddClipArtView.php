<div class="clip-art-content container">
	<div class="col-md-8">
		<div class="form-group">
			<form action="<?=CONTROLLER.'Home/upload_clipart?shop='.$shop;?>" class="dropzone dz-clickable" id="clipart-dropzone">
			  <div class="fallback">
			    <input name="file" type="file" multiple />
			  </div>
			</form>
		</div>
		<div class="form-group text-right">
			<button type="button" id="button" class="btn btn-primary">Submit</button>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<div class="category-sidebar">
				<div class="panel panel-primary">
					<div class="panel-heading"><h4>Categories</h4></div>
					<div class="panel-body cat-list">
						<?php echo $CategoryTree; ?>
					</div>
					<div class="panel-footer"></div>
				</div>
			</div>
		</div>
	</div>
</div>
