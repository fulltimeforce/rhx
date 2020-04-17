<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Portfolioexpert;
use App\Expert;
class AddColumnTypeAvailabilityToPortfolioExperts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('portfolio_expert', function (Blueprint $table) {
            //
            $table->text('availability')->after('email')->nullable();
            $table->text('slug')->after('availability')->nullable();
            $table->integer('user_id')->after('slug')->nullable();
        });

        $portfolios = Portfolioexpert::with(['expert'])->get();

        foreach ($portfolios as $key => $portfolio) {

            Portfolioexpert::where('id', $portfolio->id)
                ->update([
                    "slug" => preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower( $portfolio->expert->fullname ))
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('portfolio_expert', function (Blueprint $table) {
            //
        });
    }
}
