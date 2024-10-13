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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id');

            $table->string('email')->unique(); // Đảm bảo email là duy nhất
            $table->boolean('is_confirmed')->default(false); // Trạng thái xác nhận
            $table->string('confirmation_token')->nullable(); // Token xác nhận email (nếu có)
            $table->timestamps(); // Tạo 2 cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Đảm bảo bảng subscriptions sẽ bị xóa
        Schema::dropIfExists('subscriptions');
    }
};
