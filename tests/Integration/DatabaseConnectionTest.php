<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DatabaseConnectionTest extends KernelTestCase
{
    public function testRunsAgainstTheTestDatabaseOnMySql84(): void
    {
        $connection = self::getContainer()->get(Connection::class);

        self::assertSame('db_test', $connection->fetchOne('SELECT DATABASE()'));
        self::assertStringStartsWith('8.4.', (string) $connection->fetchOne('SELECT VERSION()'));
    }
}
