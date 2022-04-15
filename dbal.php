<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

$config = include __DIR__ . '/config.php';
include __DIR__ . '/vendor/autoload.php';

$dsn = 'mysql://' . $config['username'] . ':' . $config['password'] . '@' . $config['host'] . ':' . $config['port'] . '/' . $config['database'] . '?serverVersion=8.0';
$defaultTableOptions = [
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'engine' => 'InnoDB',
];

$connection = DriverManager::getConnection(['url' => $dsn]);
$schemaManager = $connection->createSchemaManager();

$schemaManager->dropDatabase($config['database']);
$schemaManager->createDatabase($config['database']);

$connection = DriverManager::getConnection(['url' => $dsn]);
$schemaManager = $connection->createSchemaManager();

$connection->executeQuery(<<<SQL
CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, 
  firstName VARCHAR(255) CHARACTER SET ascii NOT NULL COLLATE `ascii_general_ci`, 
  lastName VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, 
  email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, 
  PRIMARY KEY(id)) DEFAULT CHARACTER SET ascii COLLATE `ascii_general_ci` ENGINE = InnoDB;

CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, 
  name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, 
  title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, 
  description VARCHAR(255) CHARACTER SET ascii NOT NULL COLLATE `ascii_general_ci`, 
  PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
SQL
);

$fromSchema = $schemaManager->createSchema();

$toSchema = new Schema([], [], $schemaManager->createSchemaConfig()->setDefaultTableOptions($defaultTableOptions));
$userTable = $toSchema->createTable('users')
    ->addOption('charset', 'ascii')
    ->addOption('collation', 'ascii_general_ci');
$userTable->addColumn(
    'id',
    Types::INTEGER,
    [
        'autoincrement' => true,
    ]
);
$userTable->addColumn(
    'firstName',
    Types::STRING,
    [
        'length' => 255,
        'notnull' => false,
        'platformOptions' => [
            'charset' => 'ascii',
            'collation' => 'ascii_general_ci',
        ],
    ]
);
$userTable->addColumn(
    'lastName',
    Types::STRING,
    [
        'length' => 255,
        'notnull' => false,
        'platformOptions' => [
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
    ]
);
$userTable->addColumn(
    'email',
    Types::STRING,
    [
        'length' => 255,
        'notnull' => false,
        'platformOptions' => [
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_bin',
        ],
    ]
);
$userTable->setPrimaryKey(['id']);

$tagTable = $toSchema->createTable('tags');
$tagTable->addColumn(
    'id',
    Types::INTEGER,
    [
        'autoincrement' => true,
    ]
);
$tagTable->addColumn(
    'name',
    Types::STRING,
    [
        'length' => 255,
        'notnull' => false,
        'platformOptions' => [
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_bin',
        ],
    ]
);
$tagTable->addColumn(
    'title',
    Types::STRING,
    [
        'length' => 255,
        'notnull' => false,
        'platformOptions' => [
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
    ]
);
$tagTable->addColumn(
    'description',
    Types::STRING,
    [
        'length' => 255,
        'notnull' => false,
        'platformOptions' => [
            'charset' => 'ascii',
            'collation' => 'ascii_general_ci',
        ],
    ]
);
$tagTable->setPrimaryKey(['id']);

$up = $schemaManager->createComparator()->compareSchemas($fromSchema, $toSchema);
$diffSql = $up->toSql($connection->getDatabasePlatform());

if ($diffSql !== []) {
    echo 'Database up SQL is not sync!' . PHP_EOL;
    echo PHP_EOL;
    echo 'The following differences were found:' . PHP_EOL;
    foreach ($diffSql as $sql) {
        echo sprintf('%s;', str_replace(', ', ", \n  ", $sql)) . PHP_EOL;
    }
    echo PHP_EOL;
    exit(1);
} else {
    echo 'Database up SQL is sync!' . PHP_EOL;
}

$down = $schemaManager->createComparator()->compareSchemas($toSchema, $fromSchema);
$diffSql = $down->toSql($connection->getDatabasePlatform());
if ($diffSql !== []) {
    echo 'Database down SQL is not sync!' . PHP_EOL;
    echo PHP_EOL;
    echo 'The following differences were found:' . PHP_EOL;
    foreach ($diffSql as $sql) {
        echo sprintf('%s;', str_replace(', ', ", \n  ", $sql)) . PHP_EOL;
    }
    echo PHP_EOL;
} else {
    echo 'Database down SQL is sync!' . PHP_EOL;
}
