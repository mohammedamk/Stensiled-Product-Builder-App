$(document).ready(function() {

  $('#EditImages').on('change', function() {
    $('.imageView').hide();


    // imageView
    if (this.value == 'frontBack') {
      $('.imageView').show();
    }
    if (this.value == 'front') {
      $('.imageView').eq(0).show();
      // reint();
    }
});

  function reint(){
    var noImg = '<img src="/productbuilder/assets/images/no-image.png" alt="Front Image" height="100%">';
    $('.drop_box').eq(1).html(noImg);
    $('.fileValue').val(null);
  }
  $('#images').on('change', function() {
    $('.imageView').hide();


    // imageView
    if (this.value == 'frontBack') {
      $('.imageView').show();
    }
    if (this.value == 'front') {
      $('.imageView').eq(0).show();
      reint();
    }
});
    $('.create-form').on('submit', function(e) {
      e.preventDefault();
      var planid = $('#formValidator').data('planid');
      var productCount = $('#formValidator').data('productCount');
      if (planid == 0 && productCount >= 10) {
        $('html,body').scrollTop(0);
      }else{
        $('.loading,.loading progress').show();
        $('.files_error').remove();
        var formData = new FormData($(this)[0]);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', CONTROLLER+'Home/CreateProduct?shop=' + shop, true);
        xhr.onload = function(e) {
            $('.loading,.loading progress').hide();
            if (this.status == 200) {
                var r = JSON.parse(xhr.responseText);
                if (r.code == 200) {
                    window.ShowSuccesToastToast(r.message);
                    window.location.href = base_url('Home/Dashboard?shop=' + shop);
                } else if (r.code == 201) {
                    var errors = '';
                    $(r.errors).each(function(i, error) {
                        errors += '<div class="alert alert-danger files_error">\
                                    <strong>Error!</strong> ' + error.message + '</div>';
                    });
                    $('#errors').html(errors)
                }else{
                  if(r.message){
                    window.ShowErrorToast(r.message);
                  }else{
                    window.ShowErrorToast(r.msg);
                    window.GenerateSessionToken();
                  }
                }
            }
        };
        var progressBar = document.querySelector('progress');
        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
              progressBar.value = (e.loaded / e.total) * 100;
              progressBar.textContent = progressBar.value;
            }
        };
        xhr.setRequestHeader("Authorization", "Bearer " + window.sessionToken);
        xhr.send(formData);
      }
    });


    $('.edit-form').on('submit', function(e) {
        e.preventDefault();
        $('.loading,.loading progress').show();
        $('.files_error').remove();
        var product_id = $(this).find('input[name="product_id"]').val();
        var formData = new FormData($(this)[0]);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', base_url('Home/EditProduct?shop=' + shop), true);
        xhr.onload = function(e) {
            $('.loading,.loading progress').hide();
            if (this.status == 200) {
                var r = JSON.parse(xhr.responseText);
                if (r.code == 200) {
                    window.ShowSuccesToastToast(r.message);
                    window.location.href = base_url('Home/Dashboard?shop=' + shop);
                } else if (r.code == 201) {
                    var errors = '';
                    $(r.errors).each(function(i, error) {
                        errors += '<div class="alert alert-danger files_error">\
                                    <strong>Error!</strong> ' + error.message + '</div>';
                    });
                    $('#errors').html(errors)
                } else {
                  if(r.message){
                    window.ShowErrorToast(r.message);
                  }else{
                    window.ShowErrorToast(r.msg);
                    window.GenerateSessionToken();
                  }
                }
            }
        };
        var progressBar = document.querySelector('progress');
        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
              progressBar.value = (e.loaded / e.total) * 100;
              progressBar.textContent = progressBar.value;
            }
        };
        xhr.setRequestHeader("Authorization", "Bearer " + window.sessionToken);
        xhr.send(formData);
    });


    $('.create-cat').on('submit', function(e) {
        e.preventDefault();
        $('.loading').show();
        $('.files_error').remove();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: base_url('Home/create_catergory?shop=' + shop),
            type: 'post',
            dataType: 'json',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(r) {
                $('.loading').hide();
                if (r.code == 200) {
                    window.ShowSuccesToastToast.flashNotice(r.message);
                    window.location.href = base_url('Home/ClipArtView?shop=' + shop);
                } else {
                  if(r.message){
                    window.ShowErrorToast(r.message);
                  }else{
                    window.ShowErrorToast(r.msg);
                  }
                    window.GenerateSessionToken();
                }
            },
        })
    });


    $('input[name="size_array"]').bind('keyup blur', function() {
        var node = $(this);
        node.val(node.val().toUpperCase());
        node.val(node.val().replace(/[^0-9a-zA-Z,]/g, ""));
    });



    $('.delete-btn').on('click', function() {
        var id = $(this).data('id');
        var table = $(this).data('table');
        $('#loaderId').show();
        $.ajax({
            url: CONTROLLER+'Home/delete_record?shop=' + shop,
            type: 'post',
            dataType: 'json',
            data: {id: id,table: table},
            success: function(r) {
              $('#loaderId').hide();
                if (r.code == 200) {
                    window.ShowSuccesToastToast(r.msg);
                } else {
                    window.ShowErrorToast(r.msg);
                }
                window.location.reload();
            }
        })
    });
    $(document).on('click', '.delete-category_product', function() {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) {
          var id = $(this).data('id');
          var table = "categories";
          $.ajax({
              url: CONTROLLER+'Home/delete_record?shop=' + shop,
              type: 'post',
              dataType: 'json',
              data: {id: id,table: table},
              success: function(r) {
                if (r.code == 200) {
                    window.ShowSuccesToastToast(r.msg);
                    Swal.fire('Deleted!','Your record has been deleted.','success')
                } else {
                    window.ShowErrorToast(r.msg);
                }
                window.location.reload();
              }
          });
        }
      });

    });
    $('.edit-cat-btn').on('click', function(e) {
        e.preventDefault();
        $('.loading').show();
        var form = $('.edit-cat');
        var formData = new FormData(form[0]);
        formData.append('cat_id', $(this).attr('data-id'));
        $.ajax({
            url: CONTROLLER+'Home/edit_catergory?shop=' + shop,
            type: 'post',
            dataType: 'json',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(r) {
                $('.loading').hide();
                if (r.code == 200) {
                    window.ShowSuccesToastToast(r.message);
                    window.location.href = base_url('Home/ClipArtView?shop=' + shop);
                } else {
                  if(r.message){
                    window.ShowErrorToast(r.message);
                  }else{
                    window.ShowErrorToast(r.msg);
                  }
                  window.GenerateSessionToken()
                }
            },
        })
    });
    $(document).on('click', '.browse', function() {
        var file = $(this).parent().parent().parent().find('.file');
        file.trigger('click');
    });
    $(document).on('click', '.drop_box', function() {
        var file = $(this).next('.file');
        file.trigger('click');
    });
    $(document).on('change', '.file', function() {
        var input = this;
        var $this = $(this);
        $this.parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $this.prev('div').find('img').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            $this.prev('div').find('img').attr('src', 'assets/images/no-image.png');
        }
    });
    var max_fields = 50;
    var wrapper = $(".input_fields_wrap");
    var add_button = $(".add_field_button");
    var x = 1;
    $(add_button).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;
            $(wrapper).append('<div class="color-box" style="position: relative;"><input type="color" name="productcolor[]" value="#ff0000" class="color-input"><button class="btn btn-danger remove_field"><span class="fa fa-trash"></span></button></div>');
        }
        $('.color-input').on('change', function() {
            $(this).next().find('input[name="selected_color"]').attr('data-color', $(this).val());
        });
        $('.input_fields_wrap input[name="selected_color"]').on('change', function() {
            $(".face img").css("background", $(this).attr('data-color'));
        });
    });
    $(wrapper).on("click", ".remove_field", function() {
        $(this).parent('div').remove();
        x--;
    });

    $(".fancybox").fancybox();

    $(".cat-filter").on("click", function(e) {
        e.preventDefault();
        $('.loading').show();
        var $this = $(this);
        if (!$this.hasClass("active")) {
            $(".cat-filter").removeClass("active");
            $this.addClass("active");
        }
        var cat_id = $this.attr('data-cat-id');
        var per_page = $('.show_per_page').val();
        get_filtered_art(cat_id, per_page);
    });


    $('.show_per_page').change(function() {
        $('.loading').show();
        var $this = $(this);
        var cat_id = $('.cat-filter.active').attr('data-cat-id');
        var per_page = $this.val();
        get_filtered_art(cat_id, per_page);
    });
    ajax_pagination();

    function ajax_pagination() {
        $('.pagination li a').not('.pagination li a.active').click(function(e) {
            e.preventDefault();
            $('.loading').show();
            var cat_id = $('.cat-filter.active').attr('data-cat-id');
            var url = $(this).attr('href');
            var per_page = $('.show_per_page').val();
            var elm = this;
            $.ajax({
                url: url,
                type: 'post',
                data: {cat_id: cat_id,per_page: per_page,ajax:1},
                dataType: 'html',
                success: function(response) {
                    var Split = response.split('-')[0];
                    if(Split == 100){
                      console.log(response.split('-')[1]);
                      window.ShowErrorToast(response.split('-')[1]);
                      window.GenerateSessionToken();
                      $(elm).click();
                    }else{
                      $('.loading').hide();
                      $('.galleryWrap').html($(response).find('.galleryWrap').html());
                      $('.pagination-wrapper').html($(response).find('.pagination-wrapper').html());
                      $('.pagination li a').unbind('click');
                      $(".fancybox").fancybox();
                      ajax_pagination();
                      lazyload();
                      button_actions();
                    }
                }
            });
        })
    }
    lazyload();
    button_actions();

    function button_actions() {
        $('.open-lightbox').click(function() {
            var img_id = $(this).attr('data-img-id');
            $('#' + img_id).trigger('click');
        });

        $('.delete-clipart').on('click',function(){
            $('.loading').show();
            var id = $(this).data('clip-id');
            $.ajax({
                url: CONTROLLER+'Home/delete_record?shop='+shop,
                type: 'post',
                dataType: 'json',
                data: { id: id,table: 'clip_arts'},
                success: function(r) {
                    if (r.code == 200){
                        window.ShowSuccesToastToast(r.msg);
                    }else{
                        window.ShowErrorToast(r.msg);
                        window.GenerateSessionToken();
                    }
                    var cat_id = $('.cat-filter.active').attr('data-cat-id');
                    var per_page = $('.show_per_page').val();
                    var offset = $('.curlink a').text();
                    get_filtered_art(cat_id, per_page, offset);
                }
            })
        });
    }

    function get_filtered_art(cat_id, per_page, offset = null) {
        var search_query = $('input[name="search_query"]').val();
        $.ajax({
            url: CONTROLLER+'Home/filter_cliparts?shop=' + shop + '&cat_id=' + cat_id,
            type: 'post',
            dataType: 'json',
            data: {cat_id: cat_id,per_page: per_page,offset: offset,search_query:search_query},
            success: function(response) {
              $('.loading').hide();
              if(response.code && response.code == 100){
                window.ShowErrorToast(response.msg);
                window.GenerateSessionToken();
              }else{
                $('.galleryWrap').html(response.clip_images);
                $('.pagination-wrapper').html(response.pagination);
                $('.pagination li a').unbind('click');
                ajax_pagination();
                $(".fancybox").fancybox();
                lazyload();
                button_actions();
              }
            }
        });
    }
      ////////////  Not Used In Front End
    $('.search-art').submit(function(e) {
        e.preventDefault();
        $('.loading').show();
        var search_query = $(this).find('input[name="search_query"]').val();
        var per_page = $('.show_per_page').val();
        $.ajax({
            url: CONTROLLER+'Home/search_clipart?shop='+shop,
            type:'post',
            data:{
              show_per_page: per_page,
              search_query:search_query
            },
            dataType:'html',
            success:function(response){
              $('.loading').hide();
              if(response.code && response.code == 100){
                window.ShowErrorToast(response.msg);
                window.GenerateSessionToken();
              }else{
                $('.loading').hide();
                $('.galleryWrap').html($(response).find('.galleryWrap').html());
                $('.pagination-wrapper').html($(response).find('.pagination-wrapper').html());
                $('.pagination li a').unbind('click');
                ajax_pagination();
                $(".fancybox").fancybox();
                lazyload();
                button_actions()
              }
            }

        });
    });
    window.CreateCategoryTable = function(){
      var Table = $('#category-list').DataTable({
          "serverSide": true,
          "ordering": false,
          "scrollX": true,
          "ajax":{
              "url": base_url('Home/DatatableCatTree?shop='+shop+'&shop_id='+shop_id),
              "type": "POST",
              "complete": function(data) {
                var response = JSON.parse(data.responseText);
                if(response.code && response.code == 100){
                  window.ShowErrorToast(response.msg);
                  window.GenerateSessionToken();
                  Table.ajax.reload();
                }
              },
          },
          "initComplete":function(i){
              $('.edit-product').click(function() {
                  $('.edit-cat-btn').attr('data-id',$(this).data('id'));
                  var cat_id = $(this).data('id');
                  $.ajax({
                      url: base_url('Home/get_category_by_id?shop='+shop+'&cat_id='+cat_id),
                      type: 'GET',
                      dataType: 'json',
                      success:function(r){
                        if(r.code){
                          $('.close').click();
                          window.ShowErrorToast(r.msg);
                          window.GenerateSessionToken();
                        }else{
                          $('#edit').find('input[name="cat_name"]').val(r.cat_name);
                          $('#edit').find('select[name="parent_cat"]').val(r.parent_cat);
                          var image = r.cat_image;
                          if (image) {
                            var img_url = base_url('assets/images/')+image;
                            $('#edit').find('.img-box img').attr('src',img_url);
                          }
                        }
                      }
                  });

              });
          }

      });
    }

    // $(".nav a").on("click", function(){
    //    $(".nav").find(".active").removeClass("active");
    //    $(this).parent().addClass("active");
    // });

    //header links loader
    $('ul.navbar-nav li.clip_art').click(function(e){
      $('.loading').show();
    });

    $('ul.navbar-nav li.dropdown').click(function(e){
      $('.loading').hide();
    });

    $('.form-group a.back_pro').click(function(e){
      $('.loading').show();
    });

    $(function (){
      $(document).on('click', '.mq-dropdown li', function () {
        $('.loading').show();
      });
    });
});

Dropzone.options.clipartDropzone = {
    maxFilesize: 5,
    maxFiles: 100,
    addRemoveLinks: true,
    parallelUploads: 100,
    acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg,.svg",
    autoProcessQueue: false,
    headers: { 'Authorization': "Bearer " + window.sessionToken },
    init: function() {
        var self = this;
        self.options.addRemoveLinks = true;
        self.options.dictRemoveFile = "Delete";
        $("#button").click(function(e) {
            e.preventDefault();
            if ($('.cat_id:checked').length > 0) {
                self.processQueue();
            } else {
                window.ShowErrorToast('Please select atleast one category');
            }
        });

        self.on('sending', function(file, xhr, formData) {
          // console.log(window.sessionToken);
            $('.cat_id:checked').each(function(i, input) {
                formData.append('cat_ids[]', $(input).val());
            });
        });
        self.on("complete", function(file) {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                window.location.href=base_url('Home/cliparts?shop='+shop);
            }
        });
    }
}
