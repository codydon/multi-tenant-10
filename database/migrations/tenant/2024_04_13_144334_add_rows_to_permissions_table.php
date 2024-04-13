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
        Schema::table('permissions', function (Blueprint $table) {
            $table->integer('permissionGroupID')->nullable()->after("id");
            $table->string('permission_name')->nullable()->after("permissionGroupID");
            $table->longText('description')->nullable()->after("permission_name");
            $table->integer('package_id')->nullable();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('role_name')->nullable()->after("id");
            $table->longText('description')->nullable()->after("role_name");
            $table->integer('branch_id')->default(0)->after("id"); //different companies can have different roles
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            //
        });
    }
};