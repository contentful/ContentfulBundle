<?php

/**
 * This file is part of the ContentfulBundle package.
 *
 * @copyright 2016-2018 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InfoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('contentful:info')
            ->setDescription('Shows information about the configured Contentful clients');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Contentful clients');

        $info = $this->getContainer()->getParameter('contentful.clients');

        if (0 === \count($info)) {
            $io->text('There are no Contentful clients currently configured.');

            return;
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
        }, $info, \array_keys($info));

        $io->table(
            ['Name', 'Service', 'API', 'Space', 'Environment', 'Cache'],
            $data
        );
    }
}
