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
		Schema::table('users', function ($table) {
			if (!Schema::hasColumn('users', 'remember_token')) {
				$table->rememberToken(); // remember_token
			}

			if (!Schema::hasColumn('users', 'oauth_provider')) {
				$table->string('oauth_provider', 100)->nullable()->default('local');
			}
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users', function ($table) {
			$table->dropColumn(['remember_token', 'oauth_provider']);
		});
	}
};
