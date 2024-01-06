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
        Schema::create('outgoing_mails', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('number')->unique();
            $table->string('letter_number');
            $table->string('city');
            $table->date('date');
            $table->string('subject');
            $table->string('attachment');
            $table->string('receiver');
            $table->string('re_location');
            $table->string('content');
            $table->string('file')->nullable();
            $table->foreignUuid('member_id')->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing-mails');
    }
};
