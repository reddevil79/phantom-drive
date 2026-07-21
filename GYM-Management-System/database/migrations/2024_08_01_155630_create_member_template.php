<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trn_member_template', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('member_id')->index('FK_trn_member_template_mst_members_1')->comment('links to unique record id of mst_members');
            $table->integer('uid')->comment('uid devices end');
            $table->integer('fid')->comment('finger id');
            $table->integer('size')->comment('size');
            $table->integer('valid')->comment('valid');
            $table->longText('bio_temp')->comment('Fingerprint template');
            $table->timestamps();
            $table->integer('created_by')->unsigned()->index('FK_trn_subscriptions_mst_staff_3');
            $table->integer('updated_by')->unsigned()->index('FK_trn_subscriptions_mst_staff_4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
