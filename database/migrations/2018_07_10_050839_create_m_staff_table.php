<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('family_name', 15);
            $table->string('given_name', 15);
            $table->string('email')->unique();
            $table->string('password');
            $table->Integer('role')->default(10)->index('index_role');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_staff');
    }
}
