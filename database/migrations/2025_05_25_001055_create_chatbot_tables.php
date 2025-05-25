<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatbot_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->nullable();
            $table->string('keywords')->nullable();
            $table->timestamps();
        });

        // Tạo fulltext index cho tìm kiếm nhanh
        DB::statement('ALTER TABLE chatbot_faqs ADD FULLTEXT fulltext_index (question, keywords)');

        // Bảng lưu lịch sử hội thoại
        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id');
            $table->json('messages');
            $table->timestamps();

            $table->index('session_id');

            $table->foreign('user_id')
                  ->references('UserID')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatbot_conversations');
        Schema::dropIfExists('chatbot_faqs');
    }
};
