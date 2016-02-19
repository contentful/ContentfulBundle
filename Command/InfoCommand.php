<?php
/**
 * @copyright 2016 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\ContentfulBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

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
        $info = $this->getContainer()->getParameter('contentful.clients');

        if (count($info) === 0) {
            $output->writeln('<comment>There are no Contentful clients configured.</comment>');
            return;
        }

        $info = array_map(function($item, $name) {
            return [
                $name,
                $item['service'],
                $item['api'],
                $item['space']
            ];
        }, $info, array_keys($info));

        $table = new Table($output);
        $table
            ->setHeaders(array('Name', 'Service', 'API', 'Space'))
            ->setRows($info);

        $table->render();
    }
}
