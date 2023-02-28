<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Installation Process</title>
    <?php echo css_asset('bootstrap.min.css'); ?>
    <style>
      #boxshadow {
        position: relative;
        -moz-box-shadow: 1px 2px 4px rgba(0, 0, 0,0.5);
        -webkit-box-shadow: 1px 2px 4px rgba(0, 0, 0, .5);
        box-shadow: 1px 2px 4px rgba(0, 0, 0, .5);
        padding: 10px;
        background: white;
      }



      #boxshadow::after {
        content: '';
        position: absolute;
        z-index: -1; /* hide shadow behind image */
        -webkit-box-shadow: 0 15px 20px rgba(0, 0, 0, 0.3);
        -moz-box-shadow: 0 15px 20px rgba(0, 0, 0, 0.3);
        box-shadow: 0 15px 20px rgba(0, 0, 0, 0.3);
        width: 70%;
        left: 15%; /* one half of the remaining 30% */
        height: 100px;
        bottom: 0;
      }
      .container {
          width: 700px;
      }
      </style>
  </head>
  <body>
      <div class="container">
        <div id="boxshadow">
          <strong><center><h4>Verify the installation of snippet</h4></center></strong><br>
          <p>This code snippet is for adding the customizer button in your theme so that the customer can redirect to the customizer to customize the product. </p>
          <p>The snippet in the sections/product.liquid file </p>
          <p>If the code is not found please add it </p>
          <b><hr></b>
          <p><i>Below is the code snippet:</i></p>
          <div>
              <pre>
                &lt;script&gt;(function() {var form_tag = document.getElementsByClassName('product-form');var newText = document.createElement( 'a' );newText.className = 'btn';newText.href = href='/apps/custm?pid={{product.id}}';var text = document.createTextNode('Customize this Product');newText.appendChild(text);var product_collections = {{ product.collections | json }};product_collections.forEach(function (collection) {if(collection.handle == 'stensiledv2') {form_tag[0].parentNode.insertBefore( newText, form_tag[0].nextSibling );return false;}});})();&lt;/script&gt;
              </pre>
          </div>
        </div>
      </div>
  </body>
</html>
