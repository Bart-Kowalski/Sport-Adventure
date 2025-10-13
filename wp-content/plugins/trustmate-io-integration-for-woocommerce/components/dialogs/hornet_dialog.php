<?php
function hornet_dialog() {
?>
    <div class="tm-dialog-wrapper" id="hornetDialog">
        <div class="tm-dialog">
            <div class="image-wrapper">
                <img src="<?php echo plugins_url('../../assets/hornet.png', __FILE__) ?>" alt="hornet">
            </div>
            <ul>
                <li><?php echo trustmate_tr('The stars will appear on the product card near the photo and on the product tiles throughout the store') ?>.</li>
                <li><?php echo trustmate_tr('The widget displays the current number of reviews about the product and the current average rating') ?>.</li>
                <li><?php echo trustmate_tr('It can be embedded next to the main product description on the product sheet or in product lists in your store') ?>.</li>
            </ul>
            <h3><?php echo trustmate_tr('Benefits') ?>:</h3>
            <ul>
                <li><?php echo trustmate_tr('distinguishes the product by encouraging customers to click on it and make purchases') ?>.</li>
            </ul>
        </div>
    </div>
<?php
}
?>