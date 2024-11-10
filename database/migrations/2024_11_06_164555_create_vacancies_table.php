<?php

declare(strict_types=1);

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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('salary_min', 8, 2)->nullable();
            $table->decimal('salary_max', 8, 2)->nullable();
            $table->json('requirements')->nullable();
            $table->json('benefits')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->softDeletes();
            $table->timestamps();
            $table->foreignId('company_id')->constrained()->on('companies')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
