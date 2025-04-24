<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attribute_options', function (Blueprint $table) {
            $table->decimal('sale_price', 10, 2)->default(0)->after('price')->comment('Sale Price');
            $table->text('sku')->nullable()->after('sale_price')->comment('SKU');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_options', function (Blueprint $table) {
            $table->dropColumn('sale_price');
            $table->dropColumn('sku');
        });
    }
};
