<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('an_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->string('password', 255);
            $table->enum('role', ['eleve','enseignant','admin'])->default('eleve');
            $table->string('pays', 80)->nullable();
            $table->string('localite', 150)->nullable();   // ← ville/commune
            $table->string('classe', 100)->nullable();
            $table->mediumText('photo')->nullable();
            $table->tinyInteger('online')->default(0);
            $table->dateTime('last_seen')->nullable();
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('an_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('token', 64)->unique();
            $table->dateTime('expires_at');
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('user_id')->references('id')->on('an_users')->onDelete('cascade');
        });

        Schema::create('an_cours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('matiere', 80);
            $table->enum('niveau', ['primaire','college','lycee','superieur']);
            $table->string('url_externe', 500)->nullable();
            $table->unsignedInteger('prix')->default(300);
            $table->string('by_name', 100)->default('Admin');
            $table->unsignedBigInteger('by_user_id')->nullable();
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('an_fichiers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cours_id');
            $table->enum('type', ['fichier','video'])->default('fichier');
            $table->string('name', 200);
            $table->string('mime_type', 100)->nullable();
            $table->string('size_label', 20)->nullable();
            $table->mediumText('data');
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('cours_id')->references('id')->on('an_cours')->onDelete('cascade');
        });

        Schema::create('an_salles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200);
            $table->string('matiere', 80)->nullable();
            $table->string('description', 300)->nullable();
            $table->string('room', 150)->unique();
            $table->string('icon', 20)->default('📚');
            $table->string('by_name', 100)->default('Admin');
            $table->unsignedBigInteger('by_user_id')->nullable();
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('an_paiements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cours_id');
            $table->string('transaction_id', 100)->nullable();
            $table->unsignedInteger('montant')->default(0);
            $table->enum('statut', ['en_attente','valide','echoue'])->default('en_attente');
            $table->dateTime('created_at')->useCurrent();
            $table->unique(['user_id','cours_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('an_paiements');
        Schema::dropIfExists('an_fichiers');
        Schema::dropIfExists('an_salles');
        Schema::dropIfExists('an_cours');
        Schema::dropIfExists('an_sessions');
        Schema::dropIfExists('an_users');
    }
};
