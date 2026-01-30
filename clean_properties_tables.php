<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tables = ['properties', 'property_images', 'property_documents', 'property_earnings'];

foreach ($tables as $table) {
    Schema::disableForeignKeyConstraints();
    Schema::dropIfExists($table);
    Schema::enableForeignKeyConstraints();
    echo "Dropped table: $table\n";
}

// Remove migration logs
DB::table('migrations')->where('migration', 'like', '%create_properties_table%')->delete();
DB::table('migrations')->where('migration', 'like', '%create_property_images_table%')->delete();
DB::table('migrations')->where('migration', 'like', '%create_property_documents_table%')->delete();
DB::table('migrations')->where('migration', 'like', '%create_property_earnings_table%')->delete();
DB::table('migrations')->where('migration', 'like', '%create_property_related_tables%')->delete(); // Just in case

echo "Cleaned migrations table.\n";
