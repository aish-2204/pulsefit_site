<?php
declare(strict_types=1);

/**
 * Marketplace feed — PulseFit (company_id 2).
 * Default: top 5 by server counts. ?catalog=all returns every program in the catalog.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$COMPANY_ID = 2;

$catalogAll = isset($_GET['catalog']) && (string) $_GET['catalog'] === 'all';
$maxItems = $catalogAll ? 100 : 5;

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/api/marketplace-top-products.php');
$siteRootPath = dirname(dirname($script));
$origin = ($siteRootPath === '/' || $siteRootPath === '\\' || $siteRootPath === '.')
    ? $scheme . '://' . $host
    : $scheme . '://' . $host . rtrim($siteRootPath, '/');

require_once dirname(__DIR__) . '/products/bootstrap.php';

global $products;

$buildRow = static function (string $slug, array $p, string $origin): array {
    $relImg = preg_replace('#^\.\./#', '', (string) ($p['image'] ?? ''));
    $imageUrl = $origin . '/' . ltrim($relImg, '/');

    return [
        'name' => (string) ($p['name'] ?? ''),
        'description' => (string) ($p['description'] ?? ''),
        'category' => 'Fitness',
        'image_url' => $imageUrl,
        'visit_count' => 0,
        'external_url' => $origin . '/products/' . pulsefit_product_url($slug),
    ];
};

$out = [];

if ($catalogAll) {
    $slugs = array_keys($products);
    sort($slugs, SORT_STRING);
    foreach ($slugs as $slug) {
        if (!isset($products[$slug])) {
            continue;
        }
        $out[] = $buildRow($slug, $products[$slug], $origin);
        if (count($out) >= $maxItems) {
            break;
        }
    }
} else {
    $visitedItems = pulsefit_get_most_visited_products();
    if ($visitedItems !== []) {
        foreach ($visitedItems as $p) {
            $slug = (string) ($p['slug'] ?? '');
            if ($slug === '' || !isset($products[$slug])) {
                continue;
            }
            $row = $buildRow($slug, $products[$slug], $origin);
            $row['visit_count'] = (int) ($p['visit_count'] ?? 0);
            $out[] = $row;
            if (count($out) >= $maxItems) {
                break;
            }
        }
    }
}

if ($out === []) {
    $slugOrder = ['gym-access', 'yoga-flow', 'nutrition-coaching', 'team-training', 'recovery-lab'];
    foreach ($slugOrder as $slug) {
        if (!isset($products[$slug])) {
            continue;
        }
        $out[] = $buildRow($slug, $products[$slug], $origin);
        if (count($out) >= $maxItems) {
            break;
        }
    }
}

echo json_encode(
    ['company_id' => $COMPANY_ID, 'products' => $out],
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
);
