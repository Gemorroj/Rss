RSS

Requirements:
PHP >= 5.2

Example:
<?php
require 'Rss.php';

$rss = new Rss('example.com rss', 'http://example.com', 'some description');
$rss->addItem(
    'one item',
    'http://example.com',
    'some description',
    new DateTime('yesterday')
);

echo $rss->output();