<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name_normalized')->virtualAs('regexp_replace(first_name, "[^A-Za-z0-9]", "")')->index()->after('first_name');
            $table->string('last_name_normalized')->virtualAs('regexp_replace(last_name, "[^A-Za-z0-9]", "")')->index()->after('last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name_normalized',
                'last_name_normalized',
            ]);
        });
    }
};
