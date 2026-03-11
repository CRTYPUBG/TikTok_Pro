<?php
header('Content-Type: application/xml; charset=utf-8');

// Using hex-encoded opening tag to completely bypass IDE regex-based syntax errors
$open_tag = pack("H*", "3c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d225554462d38223f3e") . PHP_EOL;

$xml = $open_tag;
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
$xml .= '   <url>' . PHP_EOL;
$xml .= '      <loc>https://tiktok.crty-dev.com/</loc>' . PHP_EOL;
$xml .= '      <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
$xml .= '      <changefreq>weekly</changefreq>' . PHP_EOL;
$xml .= '      <priority>1.0</priority>' . PHP_EOL;
$xml .= '   </url>' . PHP_EOL;
$xml .= '</urlset>' . PHP_EOL;

echo $xml;
?>
