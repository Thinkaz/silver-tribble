<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReputationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reputations', function (Blueprint $table) {
            $table->id();
            $table->morphs('reputable'); // technically wrong word but sounds better than making up repable, cba finding real work
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedTinyInteger('rating');
            $table->timestamps();
            
            $table->unique([
                'user_id',
                'reputable_type',
                'reputable_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reputations');
    }
}
