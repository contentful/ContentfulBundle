<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\Command\Delivery;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InfoCommand extends Command
{
    protected static $defaultName = 'contentful:delivery:info';

    /**
     * @var array
     */
    private $info;

    public function __construct(array $info)
    {
        parent::__construct(self::$defaultName);

        $this->setDescription('Shows information about the configured Contentful delivery clients');
        $this->info = $info;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Contentful clients');

        if (0 === \count($this->info)) {
            $io->error('There are no Contentful clients currently configured.');

            return 0;
        }

        $data = \array_map(function (array $item, string $name) {
            return [
                $name,
                $item['service'],
                $item['api'],
                $item['space'],
                $item['environment'],
                $item['cache'] ?: 'Not enabled',
            ];
        }, $this->info, \array_keys($this->info));

        $io->table(
            ['Name', 'Service', 'API', 'Space', 'Environment', 'Cache'],
            $data
        );
    }
}
