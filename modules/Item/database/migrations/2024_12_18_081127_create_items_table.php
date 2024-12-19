<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Category\Model\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Category::class, 'category_id')->constrained('categories');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->enum('item_condition', ['New', 'Used', 'Good Second Hand'])->nullable();
            $table->enum('item_type', ['Sell', 'Buy', 'For Exchange'])->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('image', 255)->nullable();
            $table->string('owner_name');
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->bigInteger('version')->default(1);
            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
