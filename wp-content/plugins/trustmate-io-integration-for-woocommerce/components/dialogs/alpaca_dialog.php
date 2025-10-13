<?php
function alpaca_dialog() {
?>
    <div class="tm-dialog-wrapper" id="alpacaDialog">
        <div class="tm-dialog">
            <div class="image-wrapper">
                <img src="<?php echo plugins_url('../../assets/alpaca-en-min.png', __FILE__) ?>" alt="alpaca">
            </div>
            <ul>
                <li><?php echo trustmate_tr('Social proof widget with up to three types of popping up boxes which ensure your customers about quality of your services') ?>.</li>
                <li><?php echo trustmate_tr('Position is configurable. Thanks to unconventional images and messages they are very noticable') ?>.</li>
                <li><?php echo trustmate_tr('If you want to use custom images contact us at support@trustmate.io') ?>.</li>
                <li><?php echo trustmate_tr('Boxes does not take much space and are easy way to present your reviews') ?>.</li>
            </ul>
        </div>
    </div>
<?php
}
?>