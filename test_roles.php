<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$adminUser = \App\Models\User::where('email', 'admin@example.com')->with('role')->first();
$organiserUser = \App\Models\User::where('email', 'organiser@example.com')->with('role')->first();
$regularUser = \App\Models\User::where('email', 'user@example.com')->with('role')->first();

echo "=== Admin User ===\n";
echo "Name: " . $adminUser->name . "\n";
echo "Email: " . $adminUser->email . "\n";
echo "Role ID: " . $adminUser->role_id . "\n";
echo "Role Name: " . $adminUser->role->name . "\n";
echo "isAdmin: " . ($adminUser->isAdmin() ? 'true' : 'false') . "\n";
echo "isOrganiser: " . ($adminUser->isOrganiser() ? 'true' : 'false') . "\n";
echo "isUser: " . ($adminUser->isUser() ? 'true' : 'false') . "\n\n";

echo "=== Organiser User ===\n";
echo "Name: " . $organiserUser->name . "\n";
echo "Email: " . $organiserUser->email . "\n";
echo "Role ID: " . $organiserUser->role_id . "\n";
echo "Role Name: " . $organiserUser->role->name . "\n";
echo "isAdmin: " . ($organiserUser->isAdmin() ? 'true' : 'false') . "\n";
echo "isOrganiser: " . ($organiserUser->isOrganiser() ? 'true' : 'false') . "\n";
echo "isUser: " . ($organiserUser->isUser() ? 'true' : 'false') . "\n\n";

echo "=== Regular User ===\n";
echo "Name: " . $regularUser->name . "\n";
echo "Email: " . $regularUser->email . "\n";
echo "Role ID: " . $regularUser->role_id . "\n";
echo "Role Name: " . $regularUser->role->name . "\n";
echo "isAdmin: " . ($regularUser->isAdmin() ? 'true' : 'false') . "\n";
echo "isOrganiser: " . ($regularUser->isOrganiser() ? 'true' : 'false') . "\n";
echo "isUser: " . ($regularUser->isUser() ? 'true' : 'false') . "\n";
