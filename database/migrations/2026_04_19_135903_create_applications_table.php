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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');
            
            $table->enum('application_type',['motor','mobil','multiguna']);
            $table->bigInteger('nominal');
            $table->integer('tenor');
            $table->bigInteger('monthly_installment')->nullable();
            $table->text('notes')->nullable();
            

            $table->enum('status',['pending','approved','rejected'])->default('pending');

            $table->timestamp('filling_date')->useCurrent();
            

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
