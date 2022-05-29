<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisplayRoleIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('display_role_id')->nullable();

            $table->foreign('display_role_id')
                ->references('id')
                ->on('roles')
                ->nullOnDelete();
        });

        User::with('roles')->get()->each(function (User $user) {
            $user->displayRole()->associate(
                $user->roles->sortByDesc('precedence')->first()
            );

            $user->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('display_role_id');
        });
    }
}
