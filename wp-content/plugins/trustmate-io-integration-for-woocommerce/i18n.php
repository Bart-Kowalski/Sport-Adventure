<?php

/*
 * Translations know-how
 * https://updraftplus.com/how-to-localize-and-translate-a-wordpress-plugin-an-in-depth-guide-for-plugin-developers/
 * https://developer.wordpress.org/plugins/internationalization/security/
 * https://poedit.net/
 */

function trustmate_tr($string) {
    return __($string, 'trustmate');
}

function trustmate_get_terms_url() {
    $locale = get_locale();

    if ($locale === 'pl' || $locale === 'pl_PL') {
        return 'https://trustmate.io/regulations';
    }

    return 'https://en.trustmate.io/regulations';
}

function trustmate_get_pdpc_url() {
    $locale = get_locale();

    if ($locale === 'pl' || $locale === 'pl_PL') {
        return plugins_url('assets/TrustMate_Personal_Data_Processing_Contract_PL.pdf', __FILE__);
    }

    return plugins_url('assets/TrustMate_Personal_Data_Processing_Contract_EN.pdf', __FILE__);
}
