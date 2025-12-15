<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('customer_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('monthly_fee',12,2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_services');
    }
};
