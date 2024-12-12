<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('otp')->nullable()->after('status');
            $table->text('login_token')->nullable()->after('otp');
            DB::statement('ALTER TABLE users MODIFY COLUMN name VARCHAR(255) NULL');
            DB::statement('ALTER TABLE users MODIFY COLUMN email VARCHAR(255) NULL UNIQUE');
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
            $table->dropColumn('otp', 'login_token');
            DB::statement('ALTER TABLE users MODIFY COLUMN name VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE users MODIFY COLUMN email VARCHAR(255) NOT NULL UNIQUE');
        });
    }
};
