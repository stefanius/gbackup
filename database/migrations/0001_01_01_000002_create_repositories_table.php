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
        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->string('url')->index();
            $table->string('provider')->index();
            $table->string('protocol');
            $table->string('username');
            $table->string('repo');
            $table->string('default_branch')->comment('The default branch like Master or Main.');
            $table->integer('number_of_backups')->comment('The number of backups kept of the default branch.');
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
};
