<?php

use App\Models\Customer;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->string('gateway_name')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('amount', 16, 4)->default(0);
            $table->string('billing_type')->nullable();
            $table->string('status')->default('PENDING');
            $table->date('paid_at')->nullable();
            $table->string('external_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
