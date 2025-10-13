<?php
function gorilla_dialog() {
?>
  <div class="modal fade" id="gorilla" tabindex="-1" aria-labelledby="gorilla" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="gorilla">Gorilla</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body mx-2">
        <img class="img-fluid mb-3" src="<?php echo plugins_url('../../assets/gorilla-en-min.png', __FILE__) ?>" alt="gorilla">
        <ul>
          <li><?php echo trustmate_tr('Page-wide reviews similar to popular comments sections showing under your product') ?>.</li>
        </ul>
      </div>
    </div>
  </div>
<?php
}
?>