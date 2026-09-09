<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * old_users / old_dtr held the legacy system's data used only to
     * cross-reference records during the transition to this system.
     * That transition is complete and nothing in the app reads them anymore.
     */
    public function up(): void
    {
        // dtrs.old_id (added outside migrations, like these two tables) may
        // still carry a foreign key to old_dtr on some environments.
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('old_dtr');
        Schema::dropIfExists('old_users');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Intentionally irreversible: these tables held a one-time import
        // of legacy data that isn't reproducible from anywhere in this schema.
    }
};
