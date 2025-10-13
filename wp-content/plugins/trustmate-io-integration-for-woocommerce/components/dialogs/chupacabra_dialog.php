<?php
function chupacabra_dialog() {
?>
    <div class="tm-dialog-wrapper" id="chupacabraDialog">
        <div class="tm-dialog">
            <div class="image-wrapper">
                <img src="<?php echo plugins_url('../../assets/chupacabra-en-min.png', __FILE__) ?>" alt="chupacabra">
            </div>
            <ul>
                <li><?php echo trustmate_tr('Static widget with customer reviews sending a message "you can trust us"') ?>.</li>
                <li><?php echo trustmate_tr('Widget includes both company and product reviews') ?>.</li>
                <li><?php echo trustmate_tr('Anytime you can adjust widget outlook without developer help, even after embedding it on your site') ?>.</li>
                <h4><?php echo trustmate_tr('Why use it?') ?></h4>
                <ul>
                    <li><?php echo trustmate_tr('product sales increase') ?></li>
                    <li><?php echo trustmate_tr('customers do not leave site searching for reviews') ?></li>
                    <li><?php echo trustmate_tr('can boost your product SEO') ?></li>
                </ul>
                <h4><?php echo trustmate_tr('Microdata') ?></h4>
                <ul>
                    <li><?php echo trustmate_tr('If you enable microdata, widget will make your reviews understandable for search engines like Google') ?></li>
                    <li><?php echo trustmate_tr('Search engine results with your site will be enriched with additional data like average rating, stars, review count') ?></li>
                </ul>
            </ul>
        </div>
    </div>
<?php
}
?>