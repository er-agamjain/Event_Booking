<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Test admin user
$adminUser = User::where('email', 'admin@example.com')->with('role')->first();

echo "=== Testing Admin User ===\n";
echo "Email: " . $adminUser->email . "\n";
echo "Role ID: " . $adminUser->role_id . "\n";
echo "Role Name: " . $adminUser->role->name . "\n";
echo "isAdmin(): " . ($adminUser->isAdmin() ? 'true' : 'false') . "\n";
echo "isOrganiser(): " . ($adminUser->isOrganiser() ? 'true' : 'false') . "\n";
echo "isUser(): " . ($adminUser->isUser() ? 'true' : 'false') . "\n\n";

// Test password
echo "Testing password 'password': ";
if (\Illuminate\Support\Facades\Hash::check('password', $adminUser->password)) {
    echo "✓ Password is correct\n";
} else {
    echo "✗ Password is incorrect\n";
}

// Test role matching
echo "\nTesting role matching:\n";
echo "Role name === 'Admin': " . ($adminUser->role->name === 'Admin' ? 'true' : 'false') . "\n";
echo "Role name === 'Organiser': " . ($adminUser->role->name === 'Organiser' ? 'true' : 'false') . "\n";
echo "Role name === 'User': " . ($adminUser->role->name === 'User' ? 'true' : 'false') . "\n";
