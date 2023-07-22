<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TheBachtiarz\Auth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\Auth\Interfaces\Models\TokenResetInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(AuthUserInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(AuthUserInterface::ATTRIBUTE_USERNAME)->nullable()->unique();
            $table->string(AuthUserInterface::ATTRIBUTE_EMAIL)->nullable()->unique();
            $table->timestamp(AuthUserInterface::ATTRIBUTE_EMAIL_VERIFIED_AT)->nullable();
            $table->string(AuthUserInterface::ATTRIBUTE_PASSWORD);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create(TokenResetInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(TokenResetInterface::ATTRIBUTE_TOKEN)->unique();
            $table->string(TokenResetInterface::ATTRIBUTE_IDENTIFIER);
            $table->timestamp(TokenResetInterface::ATTRIBUTE_EXPIRESAT)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(AuthUserInterface::TABLE_NAME);
        Schema::dropIfExists(TokenResetInterface::TABLE_NAME);
    }
};
