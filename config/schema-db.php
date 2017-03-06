<?php

$schema = new \Doctrine\DBAL\Schema\Schema();

$table = $schema->createTable('user');

$table->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
$table->addColumn('firstname', 'string', ['notnull' => true, 'length' => 255]);
$table->addColumn('lastname', 'string', ['notnull' => true, 'length' => 255]);
$table->addColumn('nickname', 'string', ['notnull' => true, 'length' => 255]);
$table->addColumn('age', 'integer', ['notnull' => true, 'unsigned' => true]);
$table->addColumn('password', 'string', ['notnull' => true, 'length' => 255]);
$table->addColumn('salt', 'string', ['notnull' => true, 'length' => 255]);
$table->addColumn('roles', 'string', ['notnull' => true, 'length' => 255]);
$table->addColumn('created_at', 'datetime');
$table->setPrimaryKey(['id']);

return $schema;
