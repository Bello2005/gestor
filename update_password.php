<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'gustavo749382@gmail.com')->first();
if ($user) {
    $user->password = Hash::make('Bello');
    $user->save();
    echo "Contraseña actualizada correctamente\n";
} else {
    echo "Usuario no encontrado\n";
}