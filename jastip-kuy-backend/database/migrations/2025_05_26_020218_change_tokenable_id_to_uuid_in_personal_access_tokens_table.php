<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTokenableIdToUuidInPersonalAccessTokensTable extends Migration
{
    public function up()
    {
        // Hapus index foreign key dulu kalau ada
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn('tokenable_id');
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->uuid('tokenable_id')->after('id')->index();
        });
    }

    public function down()
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn('tokenable_id');
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('tokenable_id')->after('id')->index();
        });
    }
}
