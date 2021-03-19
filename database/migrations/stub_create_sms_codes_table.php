<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class CreateSmsCodesTable extends Migration
{
    public function up(): void
    {
        Schema::create('sms_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('serial')->nullable(false)->unique();
            $table->string('number', 16)->nullable(false);
            $table->char('code', 6)->nullable(false);
            $table->timestamp('created_at')->nullable(false)->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->timestamp('deleted_at')->nullable()->default(null);

            $table->index(['number', 'code']);
        });

        DB::statement('ALTER TABLE sms_codes MODIFY serial BIGINT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT');
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_codes');
    }
}
