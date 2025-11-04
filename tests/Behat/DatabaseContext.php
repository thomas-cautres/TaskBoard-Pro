<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\DataFixtures\ProjectFixtures;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class DatabaseContext implements Context
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @BeforeScenario
     */
    public function resetDatabase(): void
    {
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);

        $purger = new ORMPurger($this->em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $purger->purge();

        $loader = new Loader();
        $loader->addFixture(new UserFixtures());
        $loader->addFixture(new ProjectFixtures($this->userRepository));

        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }
}
