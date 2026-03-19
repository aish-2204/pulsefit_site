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

    $cookieName = 'product_visit_counts';
    $visits = [];

    if (!empty($_COOKIE[$cookieName])) {
        $decoded = json_decode($_COOKIE[$cookieName], true);
        if (is_array($decoded)) {
            $visits = $decoded;
        }
    }

    if (empty($visits)) {
        return [];
    }

    // Sort by visit count (descending)
    arsort($visits);

    // Get top 5 products
    $topSlugs = array_slice(array_keys($visits), 0, 5);
    $items = [];

    foreach ($topSlugs as $slug) {
        if (isset($products[$slug])) {
            $item = $products[$slug];
            $item['slug'] = $slug;
            $item['url'] = pulsefit_product_url($slug);
            $item['visit_count'] = $visits[$slug];
            $items[] = $item;
        }
    }

    return $items;
}
