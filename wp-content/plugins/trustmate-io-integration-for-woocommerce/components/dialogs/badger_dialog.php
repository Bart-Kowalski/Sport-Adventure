<?php
function badger_dialog() {
?>
  <div class="modal fade" id="badger" tabindex="-1" aria-labelledby="badger" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="badger">Badger</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body mx-2">
        <img class="img-fluid mb-3" src="<?php echo plugins_url('../../assets/badger.png', __FILE__) ?>" alt="badger">
        <ul>
          <li><?php echo trustmate_tr('Non-invasive badge is glued to screen\'s edge and takes little space') ?>.</li>
          <li><?php echo trustmate_tr('Click shows a modal with recent reviews') ?>.</li>
          <li><?php echo trustmate_tr('You can change display position in TrustMate panel') ?>.</li>
        </ul>
        <h4><?php echo trustmate_tr('Why use it?') ?></h4>
        <ul>
          <li><?php echo trustmate_tr('sales increase') ?></li>
          <li><?php echo trustmate_tr('customers do not leave site searching for reviews') ?></li>
          <li><?php echo trustmate_tr('can boost your SEO') ?></li>
          <li><?php echo trustmate_tr('can make search engines show stars under your results') ?></li>
        </ul>
      </div>
      </div>
    </div>
  </div>
<?php
}
?>