<?php
function ferret_dialog() {
?>
  <div class="modal fade" id="ferret" tabindex="-1" aria-labelledby="ferret" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ferret">Ferret</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body mx-2">
        <img class="img-fluid mb-3" src="<?php echo plugins_url('../../assets/ferret-pl-min.png', __FILE__) ?>" alt="ferret">
        <ul>
          <li><?php echo trustmate_tr('Dynamic "carousel" widget catches the eye of customers presenting up to the last 21 reviews. You set the scrolling speed of the visible ratings yourself') ?>.
          <li><?php echo trustmate_tr('Using the carousel you increase the attractiveness of your website to search engines, e.g. Google') ?>.
          <li><?php echo trustmate_tr('Let customers read reviews about your company without leaving your website') ?>.
          <li><?php echo trustmate_tr('The carousel is responsive and adapts to every page view and mobile devices') ?>.
        </ul>
      </div>
      </div>
    </div>
  </div>
<?php
}
?>