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
            Schema::create('customer_addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->index();
                $table->integer('type')->dafault(1);
                $table->string('name');
                $table->string('address1');
                $table->string('address2');
                $table->string('postcode');
                $table->string('neighborhood');
                $table->string('city');
                $table->string('state');
                $table->string('country');
                $table->timestamps();
                $table->softDeletes();
            });
        }


        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('customer_addresses');
        }
    };
