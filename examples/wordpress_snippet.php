<?php
/**
 * Nerkhsanj live rate in WordPress: shortcode [nerkh_rate series="dollar"].
 * The rate is cached for one minute, so the API is read at most once a minute.
 */
function nerkhsanj_live(): array
{
    $live = get_transient('nerkhsanj_live');
    if (!is_array($live)) {
        $res = wp_remote_get('https://nerkh.jahankhahan.shop/data/live.json', ['timeout' => 15]);
        $live = is_wp_error($res) ? [] : (json_decode(wp_remote_retrieve_body($res), true) ?: []);
        if ($live) {
            set_transient('nerkhsanj_live', $live, MINUTE_IN_SECONDS);
        }
    }
    return $live;
}

add_shortcode('nerkh_rate', function ($atts) {
    $atts = shortcode_atts(['series' => 'dollar'], $atts);
    $live = nerkhsanj_live();
    if (empty($live['rates'][$atts['series']])) {
        return '';
    }
    return esc_html(number_format_i18n($live['rates'][$atts['series']])) . ' تومان (منبع: <a href="https://nerkh.jahankhahan.shop/">نرخ‌سنج</a>)';
});
