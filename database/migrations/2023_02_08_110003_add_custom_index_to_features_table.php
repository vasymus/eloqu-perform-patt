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
        Schema::table('features', function (Blueprint $table) {
            $table->rawIndex("(
                case
                    when status = 'Requested' then 1
                    when status = 'Planned' then 2
                    when status = 'Completed' then 3
                end
            )", 'features_status_ranking_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('features', function (Blueprint $table) {
            $table->dropIndex(['features_status_ranking_index']);
        });
    }
};
