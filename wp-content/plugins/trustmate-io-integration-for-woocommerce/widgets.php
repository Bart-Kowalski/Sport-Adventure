<?php
include(__DIR__.'/components/switch/switch.php');

$trustmate_widget_image_count = array(
  'ferret2' => 2,
  'product_ferret2' => 2,
  'dodo2' => 4,
  'muskrat2' => 4,
  'bee' => 2,
  'chupacabra' => 1,
  'alpaca' => 2,
  'hydra' => 3,
  'hornet' => 2,
  'multihornet' => 2,
  'owl' => 6,
  'lemur' => 3,
  'badger2' => 4,
);
$trustmate_widget_descriptions = array(
  'ferret2' => array(
    'Catch your customers’ attention with this immersive widget!',
    'Showcase your average rating and the most recent reviews on auto-scrolling tiles and make your website look even cooler!',
  ),
  'product_ferret2' => array(
    'Attract your customers’ attention with this dynamic widget.',
    'Average rating, recent reviews, customer images - everything in one place!',
    'Fits like a glove and works like a dream!',
  ),
  'dodo2' => array(
    'Showcase your review count and average rating with this simple widget.',
    'No matter if your customers want to read reviews or submit their own - Dodo’s got you covered!',
    'Not too little, not too much - just perfect!',
  ),
  'muskrat2' => array(
    'Amaze your customers with this modern, interactive widget!',
    'Display your average rating and review count, showcase the most recent reviews and collect new ones with just a few clicks.',
    'Simply and intuitively.',
  ),
  'bee' => array(
    'Show off the number of reviews and display the average rating with this simple widget.',
    'Buyers will know right away that they are dealing with a trustworthy company when they enter your store.',
  ),
  'chupacabra' => array(
    'Average rating, recent reviews and customer photos displayed on static cards directly on your website.',
    'Neat and simple.',
  ),
  'alpaca' => array(
    'Attention grabber like no other.',
    'Display your average rating, number of reviews and present the most recent ones with this unmissable widget.',
    'Let Alpaca give your website that wow factor!',
  ),
  'hydra' => array(
    'Wow your customers with this versatile, modular widget!',
    'Average rating, results of product surveys, up to 50 recent reviews, expert evaluations, and the Q&A module - Hydra has everything in one place.',
    'Designed for those who want more.',
  ),
  'hornet' => array(
    'A minimalistic widget displaying average rating next to a product description.',
    'Hornet allows customers to read reviews.',
    'Simple and effective.',
  ),
  'multihornet' => array(
    'Display product average rating on category pages and other product listings.',
    'Create a star constellation in your product lists!',
  ),
  'owl' => array(
    'Want to present TrustMate-granted awards in style?',
    'Owl’s here to help!',
  ),
  'lemur' => array(
    'Get your reviews noticed with this sliding widget.',
    'Lemur is a perfect way to demonstrate your average rating, and when expanded, also latest reviews and TrustMate-granted badges.',
    'What’s not to love about it?',
  ),
  'badger2' => array(
    'Small but mighty!',
    'Bring your average rating to view and showcase the full review content by expanding this little widget.',
  ),
);
$trustmate_widget_descriptive_names = array(
  'ferret2' => 'Carousel - company reviews',
  'product_ferret2' => 'Carousel - product reviews',
  'dodo2' => 'Static box with rating (small / large)',
  'muskrat2' => 'Badge with the company\'s rating and grades distribution (edge)',
  'chupacabra' => 'Social Proof (big box)',
  'alpaca' => 'Social Proof (pop-up reviews)',
  'hydra' => 'Modular widget - product reviews, expert reviews, Q&A',
  'hornet' => 'Widget that displays the rating and reviews',
  'multihornet' => 'Widget that displays the rating and reviews',
  'owl' => 'Widget with badges',
  'lemur' => 'Sliding out widget with reviews',
  'badger2' => 'Badge - product rating (edge)',
  'bee' => 'Company rating - top bar',
);

function trustmate_render_type($widget_name) {
  $company = ['ferret2', 'dodo2', 'muskrat2', 'lemur', 'owl', 'alpaca', 'bee'];
  $product = ['product_ferret2', 'hornet', 'multihornet', 'hydra', 'badger2'];

  if (in_array($widget_name, $company)) echo trustmate_tr('Company');
  else if (in_array($widget_name, $product)) echo trustmate_tr('Product');
  else if ($widget_name === 'chupacabra') {
    echo trustmate_tr('Company'), ' / ', trustmate_tr('Product');
  }
}

function trustmate_render_widget($widget_name, $widget_display_name, $image_path_folder) {
  global $trustmate_widget_image_count;
  global $trustmate_widget_descriptions;
  global $trustmate_widget_descriptive_names;
  wp_enqueue_style('switch_style');
  $widgetId = 'trustmate_widget_' . $widget_name;
  ?>
    <div class="row mt-5">
      <div class="col-12 font-weight-bold"><div class="card-block opacity-75 fs-2 h2"><?php echo $widget_display_name ?></div></div>
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div id="<?php echo $widget_name ?>" class="carousel carousel-dark slide" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <?php for ($i = 1; $i <= $trustmate_widget_image_count[$widget_name]; $i++): ?>
              <button type="button" data-bs-target="#<?php echo $widget_name ?>" data-bs-slide-to="<?php echo $i - 1 ?>" <?php if ($i == 1) echo ' class="active" aria-current="true" '; ?> aria-label="Slide <?php echo $i ?>"></button>
            <?php endfor ?>
          </div>
          <div class="carousel-inner">
            <?php for ($i = 1; $i <= $trustmate_widget_image_count[$widget_name]; $i++): ?>
              <?php $image_name = ($widget_name === 'product_ferret2') ? 'productFerret2' : $widget_name ?>
              <?php $image_path = 'assets/' . $image_path_folder . '/' . $image_name . '_0' . $i . '.png?1' ?>
              <div class="carousel-item <?php if ($i == 1) echo "active" ?>">
                <img src="<?php echo plugins_url($image_path, __FILE__) ?>" class="d-block w-100" alt="...">
              </div>
            <?php endfor ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo $widget_name?>" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#<?php echo $widget_name?>" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
          <?php trustmate_render_switch(trustmate_tr('Turn on'), $widgetId) ?>
          <div class="my-1 mt-4"><?php echo trustmate_tr('Type') ?>: <span class="fw-bolder"><?php trustmate_render_type($widget_name) ?></span></div>
          <div class="my-1"><?php echo trustmate_tr('Name') ?>:
            <span class="fw-bolder"><?php echo trustmate_tr($trustmate_widget_descriptive_names[$widget_name]) ?></span>
          </div>
          <div class="mt-3">
            <?php foreach ($trustmate_widget_descriptions[$widget_name] as $description): ?>
                <p class='fs-6 mb-2'><?php echo trustmate_tr($description) ?></p>
            <?php endforeach ?>
          </div>
      </div>
    </div>
<?php }

function trustmate_render_widgets()
{
    $widgets = [
      ['name' => 'ferret2', 'display_name' => 'Ferret'],
      ['name' => 'product_ferret2', 'display_name' => 'ProductFerret'],
      // ['name' => 'dodo2', 'display_name' => 'Dodo'],
      ['name' => 'muskrat2', 'display_name' => 'Muskrat'],
      ['name' => 'bee', 'display_name' => 'Bee'],
      ['name' => 'chupacabra', 'display_name' => 'Chupacabra'],
      ['name' => 'alpaca', 'display_name' => 'Alpaca'],
      ['name' => 'hydra', 'display_name' => 'Hydra'],
      ['name' => 'hornet', 'display_name' => 'Hornet'],
      // ['name' => 'multihornet', 'display_name' => 'MultiHornet'],
      ['name' => 'owl', 'display_name' => 'Owl'],
      ['name' => 'lemur', 'display_name' => 'Lemur'],
      ['name' => 'badger2', 'display_name' => 'Badger'],
    ];

    $image_path_folder = 'en';
    $locale = get_locale();
    if ($locale === 'pl' || $locale === 'pl_PL') {
        $image_path_folder = 'pl';
    }

?>
    <h2 class="mt-3"><?php echo trustmate_tr('Show reviews with widgets') ?></h2>
    <form method="post" action="options.php">
      <?php settings_fields('trustmate_widget_settings'); ?>
      <div class="container-fluid">
        <?php foreach ($widgets as $widget): ?>
          <?php trustmate_render_widget($widget['name'], $widget['display_name'], $image_path_folder) ?>
        <?php endforeach ?>
      </div>
    </form>
    <br />
    <div class="notice notice-info">
        <p>
            <?php echo trustmate_tr('Remember, there are more widgets available') ?>.
            <?php echo trustmate_tr('You can put them on your site using wordpress HTML widgets') ?>.
            <?php echo trustmate_tr('Just copy the code from TrustMate panel widget section') ?>.
        </p>
    </div>
<?php } ?>
