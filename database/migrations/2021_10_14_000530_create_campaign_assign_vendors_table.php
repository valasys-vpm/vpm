<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignAssignVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_assign_vendors', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('campaign_assign_vm_id');
            $table->foreign('campaign_assign_vm_id')->references('id')->on('campaign_assign_vendor_managers')->onUpdate('cascade');

            $table->unsignedInteger('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onUpdate('cascade');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('vendors')->onUpdate('cascade');

            $table->date('display_date')->nullable();
            $table->integer('allocation')->default(0);
            $table->string('reporting_file')->nullable();

            $table->dateTime('started_at')->nullable();
            $table->dateTime('submitted_at')->nullable();

            $table->integer('assigned_by');
            $table->tinyInteger('status')->default(1)->comment('1-active,0-inactive,2-revoke');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_At')->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_assign_vendors');
    }
}
