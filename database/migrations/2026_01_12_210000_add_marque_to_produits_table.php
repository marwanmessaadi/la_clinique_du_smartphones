<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('produits', function (Blueprint $table) {
            $table->string('marque')->nullable()->after('nom');
        });
    }
    public function down() {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('marque');
        });
    }
};
