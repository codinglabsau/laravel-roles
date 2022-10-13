<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleAclsTable extends Migration
{
    public function up()
    {
        Schema::create('role_acls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('resource_type');
            $table->string('resource_id');
            $table->timestamps();

            $table->index(['role_id', 'user_id']);
            $table->index(['resource_type', 'resource_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_acls');
    }
}
