<?php
// Reference template — copy to database/migrations/<YYYY_MM_DD_HHMMSS>_create_<table>_table.php
// and adapt columns to the requested fields.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('<table>', function (Blueprint $table) {
            $table->id();

            // Replace with the requested fields. Examples of the conventions used
            // throughout this codebase:
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->integer('some_count');                 // plain int field
            $table->boolean('status')->default(true);       // active/inactive toggle
            $table->double('price', 15, 2)->default(0);      // money field

            // Foreign keys — required relation:
            // $table->foreignId('parent_id')->constrained('parents')->cascadeOnDelete();
            // Foreign keys — optional relation:
            // $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Composite index if the pair is queried together often:
            // $table->index(['col_a', 'col_b']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('<table>');
    }
};

// --- Adding a column to an EXISTING table -----------------------------------
// Never edit an already-shipped migration. Create a new file instead, e.g.
// <timestamp>_add_<column>_to_<table>_table.php:
//
// return new class extends Migration
// {
//     public function up(): void
//     {
//         Schema::table('<table>', function (Blueprint $table) {
//             $table->foreignId('new_fk_id')->nullable()->after('<existing_col>')
//                 ->constrained('<related_table>')->nullOnDelete();
//         });
//     }
//
//     public function down(): void
//     {
//         Schema::table('<table>', function (Blueprint $table) {
//             $table->dropConstrainedForeignId('new_fk_id');
//         });
//     }
// };
