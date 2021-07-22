<?php

declare(strict_types=1);

use App\Command\Hello;
use App\ViewInjection\ContentViewInjection;
use App\ViewInjection\LayoutViewInjection;
use Yiisoft\Factory\Definition\Reference;
use Yiisoft\Yii\View\CsrfViewInjection;
use Cycle\Schema\Generator;
use Spiral\Database\Database;

return [
    'app' => [
        'charset' => 'UTF-8',
        'locale' => 'en',
        'name' => 'Sam\'s Project',
    ],

    'yiisoft/aliases' => [
        'aliases' => [
            '@root' => dirname(__DIR__),
            '@assets' => '@root/public/assets',
            '@assetsUrl' => '/assets',
            '@baseUrl' => '/',
            '@message' => '@root/resources/message',
            '@npm' => '@root/node_modules',
            '@public' => '@root/public',
            '@resources' => '@root/resources',
            '@runtime' => '@root/runtime',
            '@vendor' => '@root/vendor',
            '@layout' => '@resources/views/layout',
            '@views' => '@resources/views',
            '@entity' => '@root/src/Entity'
        ],
    ],

    'yiisoft/yii-view' => [
        'injections' => [
            Reference::to(ContentViewInjection::class),
            Reference::to(CsrfViewInjection::class),
            Reference::to(LayoutViewInjection::class),
        ],
    ],

    'yiisoft/yii-console' => [
        'commands' => [
            'hello' => Hello::class,
        ],
    ],
    
    'yiisoft/yii-cycle' => [
        // DBAL config
        'dbal' => [
            // SQL query logger. Definition of Psr\Log\LoggerInterface
            'query-logger' => null,
            // Default database
            'default' => 'default',
            'aliases' => [],
            'databases' => [
                'default' => ['connection' => 'mysql'],
            ],
            'connections' => [
                'mysql' => [
                    'driver' => Spiral\Database\Driver\MySQL\MySQLDriver::class,
                    'options' => [
                        'connection' => 'mysql:host=127.0.0.1;dbname=rubrica',
                        'username' => 'root',
                        'password' => '',
                        'charset' => 'utf8',
                    ]
                ],
            ],
        ],
        
        // Cycle migration config
        'migrations' => [
            'directory' => '@root/migrations',
            'namespace' => 'App\\Migration',
            'table' => 'migration',
            'safe' => false,
        ],
        
        /**
         * Config for {@see \Yiisoft\Yii\Cycle\Factory\OrmFactory}
         * Null, classname or {@see PromiseFactoryInterface} object.
         *
         * @link https://github.com/cycle/docs/blob/master/advanced/promise.md
         */
        'orm-promise-factory' => null,

        /**
        * SchemaProvider list for {@see \Yiisoft\Yii\Cycle\Schema\Provider\Support\SchemaProviderPipeline}
        * Array of classname and {@see SchemaProviderInterface} object.
        * You can configure providers if you pass classname as key and parameters as array:
        * [
        *     SimpleCacheSchemaProvider::class => [
        *         'key' => 'my-custom-cache-key'
        *     ],
        *     FromFilesSchemaProvider::class => [
        *         'files' => ['@runtime/cycle-schema.php']
        *     ],
        *     FromConveyorSchemaProvider::class => [
        *         'generators' => [
        *              Generator\SyncTables::class, // sync table changes to database
        *          ]
        *     ],
        * ]
        */
        'schema-providers' => [
         \Yiisoft\Yii\Cycle\Schema\Provider\SimpleCacheSchemaProvider::class => [
                'key' => 'my-custom-cache-key'
         ],
         \Yiisoft\Yii\Cycle\Schema\Provider\FromFilesSchemaProvider::class => [
                'files' => ['@runtime/cycle-schema.php']
         ],
         \Yiisoft\Yii\Cycle\Schema\Provider\FromConveyorSchemaProvider::class,
         ],
        
        /**
         * Config for {@see \Yiisoft\Yii\Cycle\Schema\Conveyor\AnnotatedSchemaConveyor}
         * Annotated entity directories list.
         * {@see \Yiisoft\Aliases\Aliases} are also supported.
         */
        'annotated-entity-paths' => [
            '@entity'
         ],
    ],
    
];
