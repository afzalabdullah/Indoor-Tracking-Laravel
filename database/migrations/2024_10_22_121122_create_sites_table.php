<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('email')->unique(); // Unique email for the site
            $table->string('name'); // Name of the site
            $table->text('description'); // Description of the site
            $table->string('image_url'); // URL of the site's image
            $table->unsignedBigInteger('user_id'); // Foreign key for user
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Foreign key constraint
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('sites'); // Drop the sites table if it exists
    }
};
