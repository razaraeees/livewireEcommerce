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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('banner_video')->nullable();
            $table->integer('banner_video_status')->nullable();
            $table->string('type')->nullable();
            $table->string('link')->nullable();
            $table->string('tagline')->nullable();
            $table->string('title')->nullable();
            $table->string('alt');
            $table->tinyInteger('status');
            $table->string('mob_banner_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
