<?php

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_status', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(
                model: Chat::class
            )
                ->index()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(
                model: Message::class
            )
                ->index()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(
                model: User::class
            )
                ->index()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->boolean('is_read')
                ->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_status');
    }
};
