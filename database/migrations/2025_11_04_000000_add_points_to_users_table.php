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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('points')->default(0)->after('address')->comment('Loyalty points for members');
            $table->enum('member_level', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze')->after('points')->comment('Member tier level');
            $table->timestamp('member_since')->nullable()->after('member_level')->comment('Date when user became a member');
        });
    }

    
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['points', 'member_level', 'member_since']);
        });
    }
};
