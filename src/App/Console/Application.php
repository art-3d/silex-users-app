<?php

namespace App\Console;

use App\Application as App;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\DriverManager;

class Application extends SymfonyApplication
{
    private $silexApp;

    public function __construct(App $silexApp)
    {
        parent::__construct('Console application', '0.0.1');

        $this->silexApp = $silexApp;
        $this->silexApp->boot();

        $this->register('doctrine:database:create')
            ->setDescription('Creates the database')
            ->setCode(function (InputInterface $input, OutputInterface $output) {
                $config = require __DIR__ . '/../../../config/prod.php';

                $params = $config['db.options'];
                $dbname = $params['dbname'];
                unset($params['dbname']);

                $tmpConnection = DriverManager::getConnection($params);
                $tmpConnection->getSchemaManager()->createDatabase($dbname);
            });

        $this->register('doctrine:schema:create')
            ->setDescription('Load schema')
            ->setCode(function (InputInterface $input, OutputInterface $output) {
                $schema = require __DIR__.'/../../../config/schema-db.php';

                foreach ($schema->toSql($this->silexApp['db']->getDatabasePlatform()) as $sql) {
                    $this->silexApp['db']->exec($sql.';');
                }
            });

        $this->register('fixture:load')
            ->setDescription('Load some fixture')
            ->setCode(function (InputInterface $input, OutputInterface $output) {
                $fixtures = require __DIR__ . '/../../../config/fixture.php';

                foreach ($fixtures as $fixture) {
                    $this->silexApp['db']->insert('user', $fixture);
                }
            });
    }
}
