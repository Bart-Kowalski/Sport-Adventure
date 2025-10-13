<?php

function trustmate_render_setup_choice()
{
    wp_enqueue_style( 'config_form_style' );

    $setup_account_page = add_query_arg('action', TRUSTMATE_PAGE_SETUP_ACCOUNT);
    $create_account_page = add_query_arg('action', TRUSTMATE_PAGE_CREATE_ACCOUNT);

    ?>
    <section class="tm-install">
        <h2 style="line-height: 30px">
            TrustMate.io
        </h2>
        <div class="notice notice-info">
            <p>
                <?php echo trustmate_tr('If you already have TrustMate account') ?>
                <a href='<?php echo wp_kses_post($setup_account_page) ?>'>
                    <?php echo trustmate_tr('skip this step') ?>
                </a>
            </p>
        </div>
        <p>
            <?php echo trustmate_tr('To start collecting reviews you need TrustMate account. Fortunetly you can create it') ?>
            <?php echo trustmate_tr('within one minute. We\'ll use data from WooCommerce') ?>,
            <?php echo trustmate_tr('to make this process very easy') ?>.
            <?php echo trustmate_tr('Verify form below and click "Create TrustMate account"') ?>.
            <?php echo trustmate_tr('Company details can be updated later in TrustMate panel') ?>.
        </p>
        <form action="<?php echo wp_kses_post($create_account_page) ?>" method="POST">
            <b class="form-section-header"><?php echo trustmate_tr('Account data') ?></b>
            <div class="form-section">
                <label for="url"><?php echo trustmate_tr('Website address') ?></label><br>
                <input type="text" id="trustmate_account_url" name="url" value="<?php echo get_site_url() ?>" required/>
            </div>
            <div class="form-section">
                <label for="trustmate_account_email"><?php echo trustmate_tr('Owner\'s e-mail') ?></label><br>
                <input type="email" id="trustmate_account_email" name="email" value="<?php echo get_bloginfo('admin_email') ?>" required/>
            </div>
            <b class="form-section-header"><?php echo trustmate_tr('Company registration data') ?></b>
            <div class="form-section">
                <label for="trustmate_account_name"><?php echo trustmate_tr('Company name') ?></label><br>
                <input type="text" id="trustmate_account_name" name="name" value="<?php echo get_bloginfo('name') ?>" required/>
            </div>
            <div class="form-section">
                <label for="trustmate_account_street"><?php echo trustmate_tr('Street') ?></label><br>
                <input type="text" id="trustmate_account_street" name="street" value="<?php echo trim(get_option('woocommerce_store_address') . ' ' . get_option('woocommerce_store_address_2')) ?>" required/>
            </div>
            <div class="form-section">
                <label for="trustmate_account_city"><?php echo trustmate_tr('City') ?></label><br>
                <input type="text" id="trustmate_account_city" name="city" value="<?php echo get_option('woocommerce_store_city') ?>" required/>
            </div>
            <div class="form-section">
                <label for="trustmate_account_zip_code"><?php echo trustmate_tr('Zip code') ?></label><br>
                <input type="text" id="trustmate_account_zip_code" name="zip_code" value="<?php echo get_option('woocommerce_store_postcode') ?>" required/>
            </div>
            <div class="form-section">
                <label for="trustmate_account_country"><?php echo trustmate_tr('Country') ?></label><br>    
                <?php echo woocommerce_form_field('country', array('type' => 'country'), substr(get_option('woocommerce_default_country'), 0, 2)) ?>
            </div>
            <div class="form-section">
                <label for="trustmate_account_nip"><?php echo trustmate_tr('Tax identification number') ?></label><br>    
                <input type="text" id="trustmate_account_nip" name="nip" value=""/>
            </div>
            <p>
                <label>
                    <input type="checkbox" required>
                    <?php echo trustmate_tr('By registering I accept') ?>
                    <a href="<?php echo trustmate_get_terms_url() ?>" rel="noopener" target="_blank"><?php echo trustmate_tr('terms of service') ?></a>,
                    <?php echo trustmate_tr('including consent to be contacted by TrustMate customer service') ?>
                </label>
                <br>
                <label>
                    <input type="checkbox" required>
                    <?php echo trustmate_tr('I going to ask clients for review by e-mail') ?>
                    - <?php echo trustmate_tr('I accept') ?>
                    <a href="<?php echo trustmate_get_pdpc_url() ?>" rel="noopener" target="_blank"><?php echo trustmate_tr('the data entrustment agreement') ?></a>
                </label>
            </p>
            <p>
                <button type="submit" class='tm-button'><?php echo trustmate_tr('Create TrustMate account') ?></button>
            </p>
        </form>
    </section>
    <?php
}