<?php
// Nerkhsanj open data with plain PHP (no dependencies).
const BASE = 'https://nerkh.jahankhahan.shop';

function nerkh_get(string $path): array
{
    $context = stream_context_create(['http' => ['timeout' => 30, 'header' => "User-Agent: nerkhsanj-example\r\n"]]);
    return json_decode(file_get_contents(BASE . $path, false, $context), true);
}

/** Closing rate of the last trading day that is not after $date (holidays have no row). */
function nerkh_rate_on(array $days, string $date): ?int
{
    $found = null;
    foreach ($days as [$day, $value]) {
        if (strcmp($day, $date) > 0) {
            break;
        }
        $found = $value;
    }
    return $found;
}

$live = nerkh_get('/data/live.json');
echo "USD now: {$live['rates']['dollar']} toman on {$live['date']} {$live['time']}\n";

$archive = array_column(nerkh_get('/data/rates.json')['series'], 'days', 'key');
$then = nerkh_rate_on($archive['dollar'], '1404/07/15');
echo 'replacement cost of 830000 bought on 1404/07/15: ' . round(830000 * $live['rates']['dollar'] / $then) . "\n";

$calc = nerkh_get('/calc?' . http_build_query(['price' => 830000, 'date' => '1404/07/15', 'margin' => 20, 'format' => 'json']));
echo "server answer: {$calc['replacement_cost']} sell price: {$calc['sell_prices'][0]['price']}\n";
