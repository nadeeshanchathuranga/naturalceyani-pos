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
        Schema::table('stock_transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `stock_transactions` MODIFY COLUMN `transaction_type` ENUM('Added', 'Deducted', 'Sold', 'Deleted', 'Return') AFTER `product_id`");
            $table->unsignedBigInteger('sale_id')->nullable()->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn('sale_id');
        });
    }
};
