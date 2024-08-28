<?php include('header.php') ?>

<?php
require 'Database/MySQL.php';

$stmt = $db->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!--================Single Product Area =================-->
<div class="product_image_area" style="padding-top:0px !important">
  <div class="container">
    <div class="row s_product_inner">
      <div class="col-lg-6">
        <div class="single-prd-item">
          <img class="img-fluid" src="admin/images/<?php echo encap($result['image'])?>" width="500">
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <div class="s_product_text">
          <h3><?php echo encap($result['name'])?></h3>
          <h2><?php echo encap($result['price'])?></h2>
          <ul class="list">
            <li><a href="#"><span>Availibility</span> : In Stock</a></li>
          </ul>
          <p><?php echo encap($result['description'])?></p>
          <form action="addtocart.php" method="post">
            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
            <input type="hidden" name="id" value="<?php echo encap($result['id'])?>">
            <div class="product_count">
              <label for="qty">Quantity:</label>
              <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
               class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
               class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
            </div>
            <div class="card_area d-flex align-items-center">
              <button class="primary-btn" href="#" style="border:1px">Add to Cart</button>
              <a class="primary-btn" href="index.php">Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div><br>
<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<!-- start footer Area -->
<footer class="footer-area section_gap">
	<div class="container">
		<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
			<p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				Copyright &copy;<script>
					document.write(new Date().getFullYear());
				</script> All rights reserved |  August 2024 </p>
		</div>
	</div>
</footer>
<!-- End footer Area -->

<script src="js/vendor/jquery-2.2.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	crossorigin="anonymous"></script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/jquery.ajaxchimp.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/jquery.sticky.js"></script>
<script src="js/nouislider.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<!--gmaps Js-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
<script src="js/gmaps.min.js"></script>
<script src="js/main.js"></script>
</body>

</html>