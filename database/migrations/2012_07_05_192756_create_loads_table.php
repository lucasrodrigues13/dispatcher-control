<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loads', function (Blueprint $table) {
            $table->id(); // BIGINT

            // FK simples (se as tabelas já existirem, pode usar ->constrained('tabela'))
            $table->foreignId('dispatcher_id')->nullable()->index(); // ->constrained('dispatchers')
            $table->foreignId('carrier_id')->nullable()->index();    // ->constrained('carriers')
            $table->foreignId('employee_id')->nullable()->index();   // ->constrained('employees')

            $table->string('load_id', 255)->nullable();
            $table->string('internal_load_id', 255)->nullable();

            $table->date('creation_date')->nullable();
            $table->string('dispatcher', 255)->nullable();
            $table->string('trip', 255)->nullable();
            $table->string('year_make_model', 255)->nullable();
            $table->string('vin', 255)->nullable();
            $table->string('lot_number', 255)->nullable();

            // tinyint(1) -> boolean no Postgres
            $table->boolean('has_terminal')->nullable();

            $table->string('dispatched_to_carrier', 255)->nullable();

            // Pickup
            $table->string('pickup_name', 255)->nullable();
            $table->string('pickup_address', 255)->nullable();
            $table->string('pickup_city', 255)->nullable();
            $table->string('pickup_state', 100)->nullable();
            $table->string('pickup_zip', 20)->nullable();
            $table->date('scheduled_pickup_date')->nullable();
            $table->string('pickup_phone', 100)->nullable();
            $table->string('pickup_mobile', 50)->nullable();
            $table->date('actual_pickup_date')->nullable();

            $table->string('buyer_number', 100)->nullable();
            $table->text('pickup_notes')->nullable();

            // Delivery
            $table->string('delivery_name', 255)->nullable();
            $table->string('delivery_address', 255)->nullable();
            $table->string('delivery_city', 255)->nullable();
            $table->string('delivery_state', 100)->nullable();
            $table->string('delivery_zip', 20)->nullable();
            $table->date('scheduled_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->string('delivery_phone', 50)->nullable();
            $table->string('delivery_mobile', 50)->nullable();
            $table->text('delivery_notes')->nullable();

            // Valores
            $table->string('shipper_name', 255)->nullable();
            $table->string('shipper_phone', 50)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('expenses', 10, 2)->nullable();
            $table->decimal('broker_fee', 10, 2)->nullable();
            $table->decimal('driver_pay', 10, 2)->nullable();

            $table->string('payment_method', 100)->nullable();
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->string('paid_method', 100)->nullable();
            $table->string('reference_number', 255)->nullable();
            $table->date('receipt_date')->nullable();
            $table->string('payment_terms', 255)->nullable();
            $table->text('payment_notes')->nullable();

            $table->string('invoice_number', 100)->nullable();
            $table->text('invoice_notes')->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('invoiced_fee', 10, 2)->nullable();

            $table->string('driver', 255)->nullable();

            $table->timestamps();

            // status_move com default 'no_moved'
            $table->string('status_move', 255)->default('no_moved')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loads');
    }

};
