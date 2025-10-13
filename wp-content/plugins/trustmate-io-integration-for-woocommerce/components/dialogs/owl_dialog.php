<?php
function owl_dialog() {
?>
    <div class="tm-dialog-wrapper" id="owlDialog">
        <div class="tm-dialog">
            <div class="image-wrapper">
                <img src="<?php echo plugins_url('../../assets/owl.png', __FILE__) ?>" alt="owl">
            </div>
            <ul>
                <li><?php echo trustmate_tr('If you meet the program conditions visible on the main page of the panel, TrustMate will automatically display the badge in your store along with information about the current rating') ?>.
                <li><?php echo trustmate_tr('This will result in more buying interest from potential customers') ?>.
            </ul>
        </div>
    </div>
<?php
}
?>