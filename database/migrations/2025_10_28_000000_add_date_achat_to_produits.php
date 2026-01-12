<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('produits', 'date_achat')) {
            Schema::table('produits', function (Blueprint $table) {
                $table->dateTime('date_achat')->nullable()->after('quantite');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('produits', 'date_achat')) {
            Schema::table('produits', function (Blueprint $table) {
                $table->dropColumn('date_achat');
            });
        }
    }
};