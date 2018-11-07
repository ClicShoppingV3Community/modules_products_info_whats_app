<?php
  use ClicShopping\OM\CLICSHOPPING;
?>
<div class="<?php echo $text_position; ?> col-md-<?php echo $content_width; ?> moduleProductsInfoWhatsappGeneral">
  <a href="https://wa.me/<?php echo $phone_number; ?>?text=I'm%20interested%20in <?php echo $product_name . ' - ' . $products_model . ' - ' . $products_url; ?>" class="btn btn-success" role="button" target="_blank">
  <i style=" color: white;" class="fab fa-whatsapp moduleProductsInfoWhatsapp"></i>&nbsp;&nbsp;<?php echo CLICSHOPPING::getDef('text_whatsapp'); ?></a>
</div>