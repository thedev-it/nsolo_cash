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
    Schema::table('transactions', function (Blueprint $table) {
        $table->foreignId('saving_goal_id')
            ->nullable()
            ->constrained('savings_goals')
            ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropForeign(['saving_goal_id']);
        $table->dropColumn('saving_goal_id');
    });
}
};
