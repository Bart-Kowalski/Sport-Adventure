<?php
function muskrat_dialog() {
?>
  <div class="modal fade" id="muskrat" tabindex="-1" aria-labelledby="muskrat" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="muskrat">muskrat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body mx-2">
        <img class="img-fluid mb-3" src="<?php echo plugins_url('../../assets/muskrat.png', __FILE__) ?>" alt="muskrat">
        <ul>
          <li><?php echo trustmate_tr('Non-invasive badge is glued to screen\'s edge and takes little space') ?>.</li>
          <li><?php echo trustmate_tr('Click shows a modal with recent reviews and grade distribution') ?>.</li>
          <li><?php echo trustmate_tr('You can change display position in TrustMate panel') ?>.</li>
          <li><?php echo trustmate_tr('Widget is a finishing touch on your product page') ?>.</li>
        </ul>
        <h4><?php echo trustmate_tr('Why use it?') ?></h4>
        <ul>
          <li><?php echo trustmate_tr('product sales increase') ?></li>
          <li><?php echo trustmate_tr('customers do not leave site searching for reviews') ?></li>
          <li><?php echo trustmate_tr('can boost your product SEO') ?></li>
          <li><?php echo trustmate_tr('can make search engines show stars under product result') ?></li>
        </ul>
      </div>
      </div>
    </div>
  </div>
<?php
}
?>