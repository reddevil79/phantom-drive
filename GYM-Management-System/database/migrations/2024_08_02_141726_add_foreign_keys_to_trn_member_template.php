<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToTrnMemberTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trn_member_template', function (Blueprint $table) {
            $table->foreign('member_id', 'FK_trn_member_template_mst_members_1')->references('id')->on('mst_members')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('created_by', 'FK_trn_member_template_mst_staff_3')->references('id')->on('mst_users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('updated_by', 'FK_trn_member_template_mst_staff_4')->references('id')->on('mst_users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trn_subscriptions', function (Blueprint $table) {
            $table->dropForeign('FK_trn_member_template_mst_members_1');
            $table->dropForeign('FK_trn_member_template_mst_staff_3');
            $table->dropForeign('FK_trn_member_template_mst_staff_4');
        });
    }
}
