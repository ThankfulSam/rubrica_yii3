<?php

declare(strict_types=1);

use Cycle\ORM\PromiseFactoryInterface;
use Yiisoft\Yii\Cycle\Command\Schema;
use Yiisoft\Yii\Cycle\Command\Migration;
use Yiisoft\Yii\Cycle\Schema\SchemaProviderInterface;
use Yiisoft\Yii\Cycle\Schema\Provider\FromConveyorSchemaProvider;
use Yiisoft\Yii\Cycle\Schema\Provider\FromFilesSchemaProvider;
use Yiisoft\Yii\Cycle\Schema\Provider\SimpleCacheSchemaProvider;


return [
    // Console commands
    'yiisoft/yii-console' => [
        'commands' => [
            'cycle/schema' => Schema\SchemaCommand::class,
            'cycle/schema/php' => Schema\SchemaPhpCommand::class,
            'cycle/schema/clear' => Schema\SchemaClearCommand::class,
            'cycle/schema/rebuild' => Schema\SchemaRebuildCommand::class,
            'migrate/create' => Migration\CreateCommand::class,
            'migrate/generate' => Migration\GenerateCommand::class,
            'migrate/up' => Migration\UpCommand::class,
            'migrate/down' => Migration\DownCommand::class,
            'migrate/list' => Migration\ListCommand::class,
        ],
    ],

    /*'yiisoft/yii-cycle' => [
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
        ],*/

        /**
         * Config for {@see \Yiisoft\Yii\Cycle\Factory\OrmFactory}
         * Null, classname or {@see PromiseFactoryInterface} object.
         *
         * @link https://github.com/cycle/docs/blob/master/advanced/promise.md
         */
        /*'orm-promise-factory' => null,

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
        /*'schema-providers' => [
            \Yiisoft\Yii\Cycle\Schema\Provider\SimpleCacheSchemaProvider::class => [
                'key' => 'my-custom-cache-key'
            ],
            \Yiisoft\Yii\Cycle\Schema\Provider\FromFilesSchemaProvider::class => [
                'files' => ['@runtime/cycle-schema.php']
            ],
            \Yiisoft\Yii\Cycle\Schema\Provider\FromConveyorSchemaProvider::class,
        ],*/

        /**
         * Config for {@see \Yiisoft\Yii\Cycle\Schema\Conveyor\AnnotatedSchemaConveyor}
         * Annotated entity directories list.
         * {@see \Yiisoft\Aliases\Aliases} are also supported.
         */
        /*'annotated-entity-paths' => [
            '@entity'
        ],
    ],*/
];
