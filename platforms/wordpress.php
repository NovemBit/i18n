<?php
add_filter('redirect_canonical', function () {
    return false;
}, 10, 2);
add_action('admin_init', function () {
    remove_action('admin_head', 'wp_admin_canonical_url');
});
function i18n_redirect_fix($url)
{
    global $i18n;
    $language = $i18n->request->getLanguage();
    if ($language !== null && $language != $i18n->languages->from_language) {

        $url = $i18n->request->getTranslation()->url->translate([$url])[$url][$language];
        $url = ltrim($url, '/');
    }
    return $url;
}

add_filter('wp_redirect', 'i18n_redirect_fix');
add_filter('wp_safe_redirect', 'i18n_redirect_fix');
