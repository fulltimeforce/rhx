<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::dropIfExists('experts');
        Schema::create('experts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('last_info_update')->nullable();
            $table->string('email_address')->nullable();
            $table->string('fullname')->nullable();
            $table->string('identification_number')->nullable();
            $table->date('birthday')->nullable();
            $table->string('phone')->nullable();
            $table->string('education')->nullable();
            $table->string('address')->nullable();
            $table->string('github')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->enum('english_education',['academy','university','self'])->nullable();
            $table->string('other_knowledge')->nullable();
            $table->string('wish_knowledge')->nullable();
            $table->date('availability')->nullable();
            $table->string('salary')->nullable();
            $table->string('assessment1')->nullable();
            $table->string('result1')->nullable();
            $table->string('assessment2')->nullable();
            $table->string('result2')->nullable();
            $table->string('assessment3')->nullable();
            $table->string('result3')->nullable();
            $table->enum('focus',['backend','frontend','mobile','devops','fullstack','game'])->nullable();

            $techs = array(
                // english
                'english_speaking','english_writing','english_reading',
                //databases
                'db2','mariadb','sqlserver','mysql','mongodb','oracle','postgresql','redis','sqlite',
                //backend
                'csharp','java','python','ruby','php','cplusplus','golang',
                //frameworks/libraries
                'dotnet','dotnetcore','entityframework','linq','unity','springframework','springboot','hibernate',
                'jsf','struts','gwt','blade','django','flask','ror','laravel','codeigniter','cakephp','symfony',
                'yii','yii2','kohana','nodejs','expressjs','mongoose',
                //frontend
                'ajax','angularjs','css3','html5','jquery','less','reactjs','sass','scss','stylus','vuejs',
                'javascript','angular345','angular678','typescript',
                //mobile
                'androidjava','androidkotlin','flutter','ionic','objectivec','reactnative','swift',
                'xamarin',
                //servers
                'aws','azure','azuread','linux','windowsserver','kibana','elasticsearch','heroku',
                //aws
                's3','ec2','lambda','cognito','ebs','dynamodb','rds','aurora','awselasticsearch','ecs','eks',
                //firebase
                'cloudfirestore','authentication','cloudstorage','realtimedb','crashlytics','cloudfunctions',
                //ecommerce
                'magento','prestashop','shopifyapps','woocommerce','wpplugins','wpthemes','bigcommerce',
                'opencart',
                //excel
                'macros','tablasdinamicas','vba',
                //ci/cd
                'docker','kubernetes','jenkins','gitlab','travis','spinnaker','screwdriver',
                //tools
                'git','svn'

 
            );
            foreach($techs as $tech){
                $table->enum($tech,['unknown','basic','intermediate','advanced'])->nullable();
            }
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experts', function (Blueprint $table) {
            //
        });
    }
} 
