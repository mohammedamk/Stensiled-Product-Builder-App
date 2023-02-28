<div class="clip-art-content container">
	<nav class="navbar navbar-inverse bg-primary">
		<form class="navbar-form navbar-left">
			<label>Show</label>
			<select class="form-control show_per_page">
				<option value="10">10</option>
				<option value="25">25</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
			<label>per page</label>
		</form>
		<a href="<?=base_url('Home/AddClipArtView?shop='.$shop);?>" class="btn btn-default addclip-art">Add Clip Arts</a>
		<!-- <form class="navbar-form navbar-right search-art">
			<input class="form-control" type="search" placeholder="Search" name="search_query" aria-label="Search">
			<button class="btn btn-default" type="submit">Search</button>
		</form> -->
	</nav>
	<div class="col-md-3 clipart-filter">
	  <div class="panel-primary">
	  	<div class="panel-heading"><h4>Clip Art Filter</h4></div>
	  </div>

      <div id="MainMenu">
				<input id="searchbar" onkeyup="search_clip_art()" type="text" name="search" placeholder="Search clip art..">
        <div class="list-group panel">
        	<a href="javascript:;" data-cat-id="-1" class="list-group-item list-group-item-success cat-filter active" data-parent="#MainMenu">All</a>
        	<?php foreach($categories as $title => $category): ?>

        		<?php if(isset($category['sub_category']) && count($category['sub_category']) > 0): ?>

        			<a data-rel="CAT-<?=$category['id'];?>" href="#CAT-<?=$category['id'];?>" data-cat-id="<?=$category['id'];?>" class="list-group-item list-group-item-success cat-filter" data-toggle="collapse" data-parent="#MainMenu"><?=$title?>  <i class="fa fa-caret-down"></i></a>
        			<div class="collapse" id="CAT-<?=$category['id'];?>">
						<?php $sub_categories = $category['sub_category']; ?>
						<?php foreach($sub_categories as $sub_category): ?>
							<a data-rel="CAT-<?=$category['id'];?>" href="javascript:;" data-cat-id="<?=$sub_category['id'];?>" class="list-group-item cat-filter"><?=$sub_category['title'];?></a>
						<?php endforeach?>
        			</div>

        		<?php else:?>

        			<a href="javascript:;" data-rel="CAT-<?=$category['id'];?>" data-cat-id="<?=$category['id'];?>" class="list-group-item list-group-item-success cat-filter" data-parent="#MainMenu"><?=$title?></a>

        		<?php endif?>

        	<?php endforeach?>

        </div>
      </div>

	</div>
	<div class="col-md-9">
		<div class="galleryWrap">
			<?php foreach ($cliparts as $clipart): ?>
				<?php $img_id = str_replace('=', '', app_serialize('clipart-'.$clipart->id)); ?>
				<div class="image-wrapper imgContainer">
					  <a class="fancybox" data-fancybox="gallery" id="<?=$img_id?>" data-filter="CAT-<?=$clipart->id;?>" href="<?=base_url('assets/images/' . $clipart->clip_art)?>">
					  	<img class="lazyload"
						  	data-src="<?=ASSETS.'images/' . $clipart->clip_art?>"
						  	src="<?=ASSETS.'images/dummy-image.gif'?>" alt="" />
					  </a>
					  <div class="image-overlay">
					  	<button type="button" class="btn btn-primary open-lightbox" data-img-id="<?=$img_id?>"><i class="fa fa-eye"></i></button>
					  	<button type="button" class="btn btn-danger delete-clipart" data-clip-id="<?=$clipart->id?>"><i class="fa fa-trash"></i></button>
					  </div>
				</div>
			<?php endforeach?>
		</div>
		<div class="clearfix"></div>
		<?php if(isset($pagination)): ?>
			<div class="pagination-wrapper">
				<?=$pagination;?>
			</div>
		<?php endif ?>
	</div>
</div>
<script>
function search_clip_art() {
	let input = document.getElementById('searchbar').value
	input=input.toLowerCase();
	let x = document.getElementsByClassName('cat-filter');

	for (i = 0; i < x.length; i++) {
			if (!x[i].innerHTML.toLowerCase().includes(input)) {
					x[i].style.display="none";
			}
			else {
					x[i].style.display="list-item";
			}
	}
}
</script>
