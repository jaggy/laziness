<?php

require __DIR__ . '/vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__))->load();

$reasons = [
    'Dev > Web > Laravel',
    'Dev > Web > Vue.js',
    'Dev > Web > Tech Readings',
    'Dev > Elixir',
    'Dev > Node.js',
];

$hours = 8;
$user_id = getenv('BASECAMP_USERID');
$api = getenv('BASECAMP_URL') . '/projects/' . getenv('BASECAMP_PROJECT') . '/time_entries.xml';
$now = date('Y-m-d');

$username = getenv('BASECAMP_USERNAME');
$password = getenv('BASECAMP_PASSWORD');

$reason = $reasons[array_rand($reasons)];

$data = <<<XML
<time-entry> <person-id>{$user_id}</person-id>
    <date>{$now}</date>
    <hours>{$hours}</hours>
    <description>{$reason}</description>
</time-entry>
XML;

$xml = str_replace("\n", "", $data);

$request = <<<CURL
curl -H 'Accept: application/xml' -H 'Content-Type: application/xml' \
  -u {$username}:{$password} \
  -d '{$data}' \
    {$api}
CURL;

exec($request);


