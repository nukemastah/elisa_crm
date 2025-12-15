<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('approved')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('approvals');
    }
};
