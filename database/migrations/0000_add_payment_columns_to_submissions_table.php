<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentColumnsToSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid');
            $table->string('invoice_id')->nullable();
            $table->string('external_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'invoice_id', 'external_id', 'paid_at', 'updated_at']);
        });
    }
}
