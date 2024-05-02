
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('juegos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jugador1');
            $table->foreign('jugador1')->references('id')->on('users');
            $table->unsignedBigInteger('jugador2')->nullable();
            $table->foreign('jugador2')->references('id')->on('users');
            $table->integer('puntuacion1')->nullable();
            $table->integer('puntuacion2')->nullable();
            $table->string('estado');
            $table->unsignedBigInteger('ganador')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('juegos');
    }
};
