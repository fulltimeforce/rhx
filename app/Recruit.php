<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recruit extends Model
{
    protected $table = 'recruit';

    public $incrementing = false;
    protected $keyType = 'string';
    
    //
    protected $fillable = [
        'id',
        'fullname',
        'identification_number',
        'platform',
        'phone_number',
        'email_address',
        'profile_link',
        'file_path',
        'audio_path',
        'tech_qtn',
        'agent_id',
        'availability',
        'salary',
        'type_money',
        //FCE - EVALUATION
        'grammar_vocabulary','grammatical_forms','vocabulary','discourse_management','stretch_language','cohesive_devices',
        'hesitation','organizations_ideas','pronunciation','intonation','phonological_features','intelligible',
        'interactive_communication','interaction','fce_overall','fce_total',
        //english
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
    ];

    protected $casts = [
        'id' => 'string'
    ];

    protected static $technologies = array(
        'english' => array(
            'English',
            array(
                'english_speaking'=>'Inglés(Habla)',
                'english_writing'=>'Inglés(Escritura)',
                'english_reading'=>'Inglés(Lectura)',
            ),
        ),
        'databases' => array(
            'Databases',
            array(
                'db2'=>'DB2',
                'mariadb'=>'MariaDB',
                'sqlserver'=>"Microsoft SQL Server",
                'mysql'=>'MySQL',
                'mongodb'=>'MongoDB',
                'oracle'=>'Oracle',
                'postgresql'=>'PostgreSQL',
                'redis'=>'Redis',
                'sqlite'=>'SQLite',
            ),
        ),
        'backend' => array(
            'Backend',
            array(
                'csharp'=>'C#',
                'java'=>'Java',
                'python'=>'Python',
                'ruby'=>'Ruby',
                'php'=>'PHP',
                'cplusplus'=>'C++',
                'golang'=>'Golang',
            ),
        ),
        'frameworks' => array(
            'Frameworks/Libraries',
            array(
                'dotnet'=>'.NET',
                'dotnetcore'=>'.NET Core',
                'entityframework'=>'Entity Framework',
                'linq'=>'LINQ',
                'unity'=>'Unity',
                'springframework'=>'Spring Framework',
                'springboot'=>'Spring Boot',
                'hibernate'=>'Hibernate',
                'jsf'=>'JSF',
                'struts'=>'Struts',
                'gwt'=>'GWT',
                'blade'=>'Blade',
                'django'=>'Django',
                'flask'=>'Flask',
                'ror'=>'Ruby on Rails',
                'laravel'=>'Laravel',
                'codeigniter'=>'CodeIgniter',
                'cakephp'=>'CakePHP',
                'symfony'=>'Symfony',
                'yii'=>'Yii',
                'yii2'=>'Yii2',
                'kohana'=>'Kohana',
                'nodejs'=>'Node.js',
                'expressjs'=>'Express.js',
                'mongoose'=>'Mongoose',
                'nextjs'=>'Next.js',
                'nestjs'=>'NestJS',
                'fabricjs' => 'Fabric.js',
                'd3js' => 'D3.js'
            ),
        ),
        'frontend' => array(
            'Frontend',
            array(
                'ajax'=>'Ajax',
                'angularjs'=>'AngularJS',
                'css3'=>'CSS3',
                'html5'=>'HTML5',
                'jquery'=>'jQuery',
                'less'=>'Less',
                'reactjs'=>'React',
                'sass'=>'Sass',
                'scss'=>'SCSS',
                'stylus'=>'Stylus',
                'vuejs'=>'Vue.js',
                'javascript'=>'Javascript',
                'angular345'=>'Angular 3/4/5',
                'angular678'=>'Angular 6/7/8',
                'typescript'=>'Typescript',
                'gatsbyjs'=>'GatsbyJS',
            ),
        ),
        'mobile' => array(
            'Mobile',
            array(
                'androidjava'=>'Android Java',
                'androidkotlin'=>'Android Kotlin',
                'flutter'=>'Flutter',
                'ionic'=>'Ionic',
                'objectivec'=>'Objective-C',
                'reactnative'=>'React Native',
                'swift'=>'Swift',
                'xamarin'=>'Xamarin',
            ),
        ),
        'server' => array(
            'Server', 
            array(
                'aws'=>'Amazon Web Services',
                'azure'=>'Microsoft Azure',
                'azuread'=>'Azure Active Directory',
                'linux'=>'Linux',
                'windowsserver'=>'Windows Server',
                'kibana'=>'Kibana',
                'elasticsearch'=>'Elasticsearch',
                'heroku'=>'Heroku',
            ),
        ),
        'aws' => array(
            'Amazon Web Services',
            array(
                's3'=>'Amazon S3',
                'ec2'=>'Amazon EC2',
                'lambda'=>'AWS Lambda',
                'cognito'=>'Amazon Cognito',
                'ebs'=>'Amazon EBS',
                'dynamodb'=>'Amazon DynamoDB',
                'rds'=>'Amazon RDS',
                'aurora'=>'Amazon Aurora',
                'awselasticsearch'=>'AWS Elasticsearch',
                'ecs'=>'Amazon ECS',
                'eks'=>'Amazon EKS',
            ),
        ),
        'firebase' => array(
            'Firebase',
            array(
                'cloudfirestore'=>'Cloud Firestore',
                'authentication'=>'Authentication',
                'cloudstorage'=>'Cloud Storage',
                'realtimedb'=>'Realtime Database',
                'crashlytics'=>'Crashlytics',
                'cloudfunctions'=>'Cloud Functions',
            ),
        ),
        'ecommerce' => array(
            'Ecommerce',
            array(
                'magento'=>'Magento',
                'prestashop'=>'PrestaShop',
                'shopifyapps'=>'Shopify Apps',
                'woocommerce'=>'Woocommerce',
                'wpplugins'=>'Wordpress Plugins',
                'wpthemes'=>'Wordpress Themes',
                'bigcommerce'=>'BigCommerce',
                'opencart'=>'OpenCart',
            ),
        ),
        'excel' => array(
            'Microsoft Excel',
            array(
                'macros'=>'Macros',
                'tablasdinamicas'=>'Tablas Dinámicas',
                'vba'=>'VBA',
            ),
        ),
        'cicd' => array(
            'CI/CD',
            array(
                'docker'=>'Docker',
                'kubernetes'=>'Kubernetes',
                'jenkins'=>'Jenkins',
                'gitlab'=>'Gitlab CI',
                'travis'=>'Travis CI',
                'spinnaker'=>'Spinnaker',
                'screwdriver'=>'Screwdriver',
            ),
        ),
        'unity' => array(
            'Unity',
            array(
                '3dmodeling' => '3D Modeling',
                'csharpu' => 'Unity C#',
                'animation' => 'Animation',
                'physics' => 'Physics',
                'networking' => 'Networking',
                'vr' => 'Virtual Reality',
                'graphics' => 'Graphics',
                'ui' => 'UI',
            )
        ),
        'others' => array(
            'Others',
            array(
                'git'=>'Git',
                'svn'=>'SVN',
            ),
        ),
    );

    protected static $fce_values = array(
        "C2"    => 20,
        "C1"    => 17,
        "B2"    => 15,
        "B1"    => 12,
        "A2"    => 8,
        "A1"    => 0,
    );

    protected static $fce_values_total = array(
        "A1-"   => array(0,2),
        "A1"    => array(2,4),
        "A1+"   => array(4,6),
        "A2-"   => array(6,(22/3)),
        "A2"    => array((22/3),(26/3)),
        "A2+"   => array((26/3),10),
        "B1-"   => array(10,(34/3)),
        "B1"    => array((34/3),(38/3)),
        "B1+"   => array((38/3),14),
        "B2-"   => array(14,(44/3)),
        "B2"    => array((44/3),(46/3)),
        "B2+"   => array((46/3),16),
        "C1-"   => array(16,(50/3)),
        "C1"    => array((50/3),(52/3)),
        "C1+"   => array((52/3),18),
        "C2-"   => array(18,(56/3)),
        "C2"    => array((56/3),(58/3)),
        "C2+"   => array((58/3),21),
    );

    public static function getTechnologies(){
        return self::$technologies;
    }

    public static function getTechnologyName($db_tech_name){
        foreach (self::$technologies as $key => $value) {
            foreach ($value[1] as $key2 => $value2) {
                if($key2 == $db_tech_name){
                    return $value2;
                }
            }
        }
    }

    public static function getFceValue($key){
        return self::$fce_values[$key];
    }

    public static function calculateOveral($total){
        foreach (self::$fce_values_total as $k => $v) {
            if( $v[0] <= $total && $v[1] > $total ){
                return $k;
            }
        }
    }
}
