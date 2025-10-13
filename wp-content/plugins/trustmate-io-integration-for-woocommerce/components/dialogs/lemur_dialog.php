<?php
function lemur_dialog() {
?>
    <div class="tm-dialog-wrapper" id="lemurDialog">
        <div class="tm-dialog">
            <div class="image-wrapper">
                <img src="<?php echo plugins_url('../../assets/lemur-pl-min.png', __FILE__) ?>" alt="lemur">
            </div>
            <ul>
                <li><?php echo trustmate_tr('Non-invasive badge is glued to screen\'s edge and takes little space') ?>.</li>
                <li><?php echo trustmate_tr('Click shows a modal with recent reviews') ?>.</li>
                <li><?php echo trustmate_tr('You can change display position in TrustMate panel') ?>.</li>
                <h4><?php echo trustmate_tr('Why use it?') ?></h4>
                <ul>
                    <li><?php echo trustmate_tr('sales increase') ?></li>
                    <li><?php echo trustmate_tr('customers do not leave site searching for reviews') ?></li>
                    <li><?php echo trustmate_tr('can boost your SEO') ?></li>
                    <li><?php echo trustmate_tr('can make search engines show stars under your results') ?></li>
                </ul>
            </ul>
        </div>
    </div>
<?php
}
?>