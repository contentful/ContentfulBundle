<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\ContentfulBundle\Unit\Command\Delivery;

use Contentful\ContentfulBundle\Command\Delivery\DebugCommand;
use Contentful\Delivery\Client;
use Contentful\Tests\ContentfulBundle\TestCase;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class DebugCommandTest extends TestCase
{
    public function testOutput()
    {
        $client = new Client('b4c0n73n7fu1', 'cfexampleapi', 'master');
        $configurations = [
            'main' => ['service' => 'default.client'],
        ];

        $command = new DebugCommand([$client], $configurations);

        $input = new ArrayInput([
            'client-name' => 'main',
        ]);
        $output = new BufferedOutput();
        // Avoid having column wrapped in tests
        \putenv('COLUMNS=200');
        $command->run($input, $output);

        $this->assertSame($this->getFixtureContent('output.txt'), $output->fetch());

        // The command will use a default value if
        // only one client is configured and no value is given
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $command->run($input, $output);

        $this->assertSame($this->getFixtureContent('output.txt'), $output->fetch());
    }

    public function testInvalidClient()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find the requested client "invalid", use "contentful:delivery:info" to check the configured clients.');

        $client = new Client('b4c0n73n7fu1', 'cfexampleapi', 'master');
        $configurations = [
            'main' => ['service' => 'default.client'],
        ];

        $command = new DebugCommand([$client], $configurations);

        $input = new ArrayInput([
            'client-name' => 'invalid',
        ]);
        $output = new BufferedOutput();
        $command->run($input, $output);
    }

    public function testDataCannotBeFetched()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Requested service was found, but data could not be loaded. Try checking client credentials.');

        $client = new Client('invalid', 'cfexampleapi', 'master');
        $configurations = [
            'main' => ['service' => 'default.client'],
        ];

        $command = new DebugCommand([$client], $configurations);

        $input = new ArrayInput([
            'client-name' => 'main',
        ]);
        $output = new BufferedOutput();
        $command->run($input, $output);
    }
}
