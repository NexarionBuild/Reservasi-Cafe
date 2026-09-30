<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pelanggan') && !Schema::hasColumn('pelanggan', 'email')) {
            Schema::table('pelanggan', function (Blueprint $table) {
                $table->string('email')->nullable()->unique();
            });
        }

        if (Schema::hasTable('reservasi') && !Schema::hasColumn('reservasi', 'status_kehadiran')) {
            Schema::table('reservasi', function (Blueprint $table) {
                $table->string('status_kehadiran', 20)->default('belum_hadir');
            });
        }

        if (Schema::hasTable('meja')) {
            DB::table('meja')->where('ruangan', 'Indor1')->update(['ruangan' => 'Indoor']);
            DB::table('meja')->where('ruangan', 'Indor2')->update(['ruangan' => 'Indoor']);
            DB::table('meja')->where('ruangan', 'Outdor')->update(['ruangan' => 'Outdoor']);
        }

        if (Schema::hasTable('reservasi')) {
            DB::table('reservasi')->whereIn('ruangan', ['Indor1', 'Indor2'])->update(['ruangan' => 'Indoor']);
            DB::table('reservasi')->where('ruangan', 'Outdor')->update(['ruangan' => 'Outdoor']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('reservasi') && Schema::hasColumn('reservasi', 'status_kehadiran')) {
            Schema::table('reservasi', function (Blueprint $table) {
                $table->dropColumn('status_kehadiran');
            });
        }

        if (Schema::hasTable('pelanggan') && Schema::hasColumn('pelanggan', 'email')) {
            Schema::table('pelanggan', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
};
