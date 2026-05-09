<?php

require_once __DIR__ . '/catalog.php';

function pulsefit_track_product_visit(string $slug): void
{
    global $products;

    if (!isset($products[$slug])) {
        return;
    }

    $cookieName = 'recent_products';
    $recent = [];

    if (!empty($_COOKIE[$cookieName])) {
        $decoded = json_decode($_COOKIE[$cookieName], true);
        if (is_array($decoded)) {
            $recent = $decoded;
        }
    }

    $recent = array_values(array_filter($recent, function ($existing) use ($slug) {
        return $existing !== $slug;
    }));

    array_unshift($recent, $slug);
    $recent = array_slice($recent, 0, 5);

    setcookie($cookieName, json_encode($recent), time() + 60 * 60 * 24 * 30, '/');

    // Track visit count
    pulsefit_track_product_visit_count($slug);
}

function pulsefit_track_product_visit_count(string $slug): void
{
    $cookieName = 'product_visit_counts';
    $visits = [];

    if (!empty($_COOKIE[$cookieName])) {
        $decoded = json_decode($_COOKIE[$cookieName], true);
        if (is_array($decoded)) {
            $visits = $decoded;
        }
    }

    // Increment the count for this product
    if (isset($visits[$slug])) {
        $visits[$slug]++;
    } else {
        $visits[$slug] = 1;
    }

    setcookie($cookieName, json_encode($visits), time() + 60 * 60 * 24 * 365, '/');
    pulsefit_aggregate_increment_slug($slug);
}

function pulsefit_aggregate_storage_path(): string
{
    return dirname(__DIR__) . '/data/pulsefit_product_counts.json';
}

function pulsefit_aggregate_increment_slug(string $slug): void
{
    $slug = trim($slug);
    if ($slug === '') {
        return;
    }
    $path = pulsefit_aggregate_storage_path();
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $fp = fopen($path, 'c+');
    if ($fp === false) {
        return;
    }
    try {
        flock($fp, LOCK_EX);
        $counts = [];
        $stat = fstat($fp);
        if ($stat && $stat['size'] > 0) {
            rewind($fp);
            $raw = stream_get_contents($fp);
            $decoded = $raw !== false && $raw !== '' ? json_decode($raw, true) : [];
            if (is_array($decoded)) {
                $counts = array_map('intval', $decoded);
            }
        }
        $counts[$slug] = ($counts[$slug] ?? 0) + 1;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($counts));
        fflush($fp);
    } finally {
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}

/** @return array<string, int> */
function pulsefit_aggregate_load_counts(): array
{
    $path = pulsefit_aggregate_storage_path();
    if (!is_file($path)) {
        return [];
    }
    $raw = file_get_contents($path);
    if ($raw === false || $raw === '') {
        return [];
    }
    $data = json_decode($raw, true);

    return is_array($data) ? array_map('intval', $data) : [];
}

function pulsefit_get_recent_products(): array
{
    global $products;

    $cookieName = 'recent_products';
    $recent = [];

    if (!empty($_COOKIE[$cookieName])) {
        $decoded = json_decode($_COOKIE[$cookieName], true);
        if (is_array($decoded)) {
            $recent = $decoded;
        }
    }

    $items = [];

    foreach ($recent as $slug) {
        if (isset($products[$slug])) {
            $item = $products[$slug];
            $item['slug'] = $slug;
            $item['url'] = pulsefit_product_url($slug);
            $items[] = $item;
        }
    }

    return $items;
}

function pulsefit_get_most_visited_products(): array
{
    global $products;

    $visits = pulsefit_aggregate_load_counts();
    $cookieName = 'product_visit_counts';
    if ($visits === [] && !empty($_COOKIE[$cookieName])) {
        $decoded = json_decode($_COOKIE[$cookieName], true);
        if (is_array($decoded)) {
            $visits = $decoded;
        }
    }

    if (empty($visits)) {
        return [];
    }

    arsort($visits);

    $topSlugs = array_slice(array_keys($visits), 0, 5);
    $items = [];

    foreach ($topSlugs as $slug) {
        if (isset($products[$slug])) {
            $item = $products[$slug];
            $item['slug'] = $slug;
            $item['url'] = pulsefit_product_url($slug);
            $item['visit_count'] = (int) ($visits[$slug] ?? 0);
            $items[] = $item;
        }
    }

    return $items;
}
