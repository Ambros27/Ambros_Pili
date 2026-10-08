<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained('categories')->nullOnDelete();
        });

        $categoryNames = [
            'electronics' => 'Elektronik',
            'sport' => 'Olahraga',
            'furniture' => 'Mebel',
            'toys' => 'Mainan',
        ];

        foreach ($categoryNames as $legacyName => $name) {
            DB::table('categories')->updateOrInsert(
                ['name' => $name],
                ['created_at' => now(), 'updated_at' => now()]
            );

            $categoryId = DB::table('categories')->where('name', $name)->value('id');

            DB::table('products')
                ->whereRaw('LOWER(category) = ?', [$legacyName])
                ->update(['category_id' => $categoryId]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};