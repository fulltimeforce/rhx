<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToRecruitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruit', function (Blueprint $table) {
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
                'yii','yii2','kohana','nodejs','expressjs','mongoose','nextjs','nestjs','fabricjs' , 'd3js',
                //frontend
                'ajax','angularjs','css3','html5','jquery','less','reactjs','sass','scss','stylus','vuejs',
                'javascript','angular345','angular678','typescript','gatsbyjs',
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
                //unity
                '3dmodeling','csharpu','animation','physics','networking','vr','graphics','ui',
                //Others
                'git','svn',
            );
            foreach($techs as $tech){
                $table->enum($tech,['unknown','basic','intermediate','advanced'])->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruit', function (Blueprint $table) {
            //
        });
    }
}
