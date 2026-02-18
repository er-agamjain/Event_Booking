<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

DB::table('migrations')->insert([
    'migration' => '0001_01_01_000003_create_sessions_table',
    'batch' => 1
]);

echo "Sessions migration marked as completed.\n";
