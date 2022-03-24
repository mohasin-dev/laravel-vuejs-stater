<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('module', 100)->after('guard_name');
            $table->string('permission', 20)->nullable()->after('module');
            $table->integer('parent_permission')->default(0)->after('permission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('module');
            $table->dropColumn('permission');
            $table->dropColumn('parent_permission');
        });
    }
}
