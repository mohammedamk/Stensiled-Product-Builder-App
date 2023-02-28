<html>
<title>Home</title>
<head>
  <?php echo css_asset('plancss.css'); ?>
  <style>
  @import url("https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800");
body {
  background-color: #9aa3a16e;
  font-family: 'Open Sans', sans-serif;
  text-align: center;
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.planContainer {
  display: flex;
  flex-wrap: wrap;
  margin: 1em;
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  justify-content: center;
  margin-top: 8%;
}

.plan {
  background: white;
  width: 20em;
  box-sizing: border-box;
  text-align: center;
  margin: 1em;
  margin-bottom: 1em;
}
.plan .titleContainer {
  background-color: #f3f3f3;
  padding: 1em;
}
.plan .titleContainer .title {
  font-size: 1.45em;
  text-transform: uppercase;
  color: #1abc9c;
  font-weight: 700;
}
.plan .infoContainer {
  padding: 1em;
  color: #2d3b48;
  box-sizing: border-box;
}
.plan .infoContainer .price {
  font-size: 1.35em;
  padding: 1em 0;
  font-weight: 600;
  margin-top: 0;
  display: inline-block;
  width: 80%;
}
.plan .infoContainer .price p {
  font-size: 1.35em;
  display: inline-block;
  margin: 0;
}
.plan .infoContainer .price span {
  font-size: 1.0125em;
  display: inline-block;
}
.plan .infoContainer .desc {
  padding-bottom: 1em;
  border-bottom: 2px solid #f3f3f3;
  margin: 0 auto;
  width: 90%;
}
.plan .infoContainer .desc em {
  font-size: 1em;
  font-weight: 500;
}
.plan .infoContainer .features {
  font-size: 1em;
  list-style: none;
  padding-left: 0;
}
.plan .infoContainer .features li {
  padding: 0.5em;
}
.plan .infoContainer .selectPlan {
  border: 2px solid #1abc9c;
  padding: 0.75em 1em;
  border-radius: 2.5em;
  cursor: pointer;
  transition: all 0.25s;
  margin: 1em auto;
  box-sizing: border-box;
  max-width: 70%;
  display: block;
  font-weight: 700;
}
.plan .infoContainer .selectPlan:hover {
  background-color: #1abc9c;
  color: white;
}

@media screen and (max-width: 25em) {
  .planContainer {
    margin: 0;
  }
  .planContainer .plan {
    width: 100%;
    margin: 1em 0;
  }
}
  .dashboard{
    background: #228799;
    border-radius: 2px;
    font-weight: 700;
    font-size: 1.4em;
    color: #FFF;
    padding: 10px 16px;
    margin-left: 12px;
    position: absolute;
    top: 30px;
    left: 30px;
  }
  /* .test{
    margin: 30px auto 0;
    max-width: 960px;
  } */
  </style>
</head>
<body>
  <div class="Test">
    <?php $shop = $this->input->get('shop'); ?>
    <a href="<?=base_url('Home/Dashboard?shop='.$shop);?>" class="dashboard">Back to Dashboard</a><br>
  </div>
  <div class="planContainer">
    <div class="plan">
      <div class="titleContainer">
        <div class="title">Free Plan</div>
      </div>
      <div class="infoContainer">
        <div class="price">
          <p>$00.00 </p><span>/mo</span>
        </div>
        <div class="p desc"><em>Add your first 10 products at free of cost.</em></div>
        <ul class="features">
          <li><strong>1 Products</strong></li>
          <li>Clip Art Categories</li>
          <li>Upload Clip Art</li>
          <li>Load Clip Art From Web</li>
          <?php if ($plans == 1) { $visible ="style='visibility:hidden'"; }else { $visible ="style=''"; }?>
        </ul><a class="selectPlan" <?php echo $visible ?>>ACTIVATED</a>
      </div>
    </div>
    <div class="plan">
      <div class="titleContainer">
        <div class="title">Basic Plan</div>
      </div>
      <div class="infoContainer">
        <div class="price">
          <p>$9.99</p><span>/mo</span>
        </div>
        <div class="p desc"><em>Get basic plan and add unlimited products.</em></div>
        <ul class="features">
          <li><strong>50 Products</strong></li>
          <li>Clip Art Categories</li>
          <li>Upload Clip Art</li>
          <li>Load Clip Art From Web</li>
        </ul>
        <?php  if ($plans == 0 || $plans == 2 || $plans == 3) {?>
          <a href="<?php echo base_url(); ?>Auth/CreateCharge?shop=<?php echo $shop; ?>&price=9.99" class="selectPlan">ACTIVATE PLAN</a>
      <?php  }else{?>
        <a class="selectPlan">ACTIVATED</a>
        <a href="<?php echo base_url(); ?>Auth/CancelCharge?shop=<?php echo $shop; ?>" class="selectPlan">DEACTIVATE PLAN</a>
        <?php } ?>
      </div>
    </div>
    <div class="plan">
      <div class="titleContainer">
        <div class="title">Basic <sup><svg viewBox="0 0 448 448" xmlns="http://www.w3.org/2000/svg" width="15pt" height="15pt"><path fill="#1abc9c" d="m408 184h-136c-4.417969 0-8-3.582031-8-8v-136c0-22.089844-17.910156-40-40-40s-40 17.910156-40 40v136c0 4.417969-3.582031 8-8 8h-136c-22.089844 0-40 17.910156-40 40s17.910156 40 40 40h136c4.417969 0 8 3.582031 8 8v136c0 22.089844 17.910156 40 40 40s40-17.910156 40-40v-136c0-4.417969 3.582031-8 8-8h136c22.089844 0 40-17.910156 40-40s-17.910156-40-40-40zm0 0"></path></svg></sup> Plan</div>
      </div>
      <div class="infoContainer">
        <div class="price">
          <p>$24.99</p><span>/mo</span>
        </div>
        <div class="p desc"><em>Get basic plan and add unlimited products.</em></div>
        <ul class="features">
          <li><strong>200 Products</strong></li>
          <li><strong>Sync Products</strong></li>
          <li>Clip Art Categories</li>
          <li>Upload Clip Art</li>
          <li>Load Clip Art From Web</li>
        </ul>
        <?php  if ($plans == 0 || $plans == 1 || $plans == 3) {?>
          <a href="<?php echo base_url(); ?>Auth/CreateCharge?shop=<?php echo $shop; ?>&price=24.99" class="selectPlan">ACTIVATE PLAN</a>
      <?php  }else{?>
        <a class="selectPlan">ACTIVATED</a>
        <a href="<?php echo base_url(); ?>Auth/CancelCharge?shop=<?php echo $shop; ?>" class="selectPlan">DEACTIVATE PLAN</a>
        <?php } ?>
      </div>
    </div>
    <div class="plan">
      <div class="titleContainer">
        <div class="title">Platinum Plan</div>
      </div>
      <div class="infoContainer">
        <div class="price">
          <p>$44.99</p><span>/mo</span>
        </div>
        <div class="p desc"><em>Get basic plan and add unlimited products.</em></div>
        <ul class="features">
          <li><strong>Unlimited Products</strong></li>
          <li><strong>Sync Products</strong></li>
          <li><strong>Front+Back Customization</strong> <sup><plan style="color:skyblue;">(New Feature)</plan></sup> </li>
          <li>Clip Art Categories</li>
          <li>Upload Clip Art</li>
          <li>Load Clip Art From Web</li>
        </ul>
        <!-- <a href="#" class="selectPlan">COMMING SOON</a> -->
        <?php  if ($plans == 0 || $plans == 1 || $plans == 2) {?>
          <a href="<?php echo base_url(); ?>Auth/CreateCharge?shop=<?php echo $shop; ?>&price=44.99" class="selectPlan">ACTIVATE PLAN</a>
      <?php  }else{?>
        <a class="selectPlan">ACTIVATED</a>
        <a href="<?php echo base_url(); ?>Auth/CancelCharge?shop=<?php echo $shop; ?>" class="selectPlan">DEACTIVATE PLAN</a>
        <?php } ?>
      </div>
    </div>
  </div>
</body>

</html
