<h2><?php echo trustmate_tr('FAQ') ?></h2>
<dl>
<dt><b><?php echo trustmate_tr('Can I ask only for company reviews') ?>?</b></dt>
<dd>
   <?php echo trustmate_tr('Yes. You need to mark company invitation configuration as automatic and make sure no product invitation configuration is marked as such') ?>.
</dd>
<dt><b><?php echo trustmate_tr('Can I ask only for product reviews') ?>?</b></dt>
<dd>
   <?php echo trustmate_tr('Yes. You need to mark product invitation configuration as automatic and make sure no company invitation configuration is marked as such') ?>.
   <?php echo trustmate_tr('Remember, for product invitations to work you need to load product feed in TrustMate settings') ?>.
</dd>
<dt><b><?php echo trustmate_tr('Can I ask for both company and product reviews') ?>?</b></dt>
<dd>
   <?php echo trustmate_tr('Yes. You need to mark both company and product invitation configurations as automatic') ?>.
   <?php echo trustmate_tr('Is such case we recommend configuring company invitations to send at least couple days befor product') ?>.
   <?php echo trustmate_tr('Sending them together may lower conversion rate') ?>.
</dd>
<dt><b><?php echo trustmate_tr('What is UUID') ?>?</b></dt>
<dd>
   <?php echo trustmate_tr('UUID code is a unique identifier for your TrustMate\'s account') ?>.
   <?php echo trustmate_tr('You will find it in <em>Integration</em> section') ?>.
</dd>
<dt><b><?php echo trustmate_tr('How can I add widget with grades and reviews') ?>?</b></dt>
<dd>
   <?php echo trustmate_tr('Widget which stick to screen edge can be turned on with one click in plugin configuration') ?>.
   <?php echo trustmate_tr('All other widget you can embed on your site using Wordpress features: widgets and themes') ?>.
<dt><b><?php echo trustmate_tr('When should I enable "Ask for permission to send review invitation"') ?>?</b></dt>
<dd>
   <?php echo trustmate_tr('Always when you do not collect such consent in previous transaction steps and your rules document do not handle the issue') ?>.
</dd>
<dt><b><?php echo trustmate_tr('How can I hide WooCommerce product reviews') ?>?</b></dt>
<dd>
   <?php echo trustmate_tr('You need to turn off default WooCommerce reviews in: <em>WooCommerce → Settings → Products → Enable product reviews</em>') ?>.</em>
</dd>
<dt><b><?php echo trustmate_tr('Do you support WPML?') ?>?</b></dt>
<dd>
   <?php echo trustmate_tr('Yes, but keep in mind that you need to add invitation configurations for all languages you want to use in TrustMate.io panel') ?>.</em>
</dd>
<dt><b><?php echo trustmate_tr('How I can decide which on my product category is sent to TrustMate.io?') ?>?</b></dt>
<dd>
   <?php echo trustmate_tr('If you use multiple categories for your product the only way is to use primary category feature from Yoast SEO plugin') ?>.</em>
</dd>
</dl>
