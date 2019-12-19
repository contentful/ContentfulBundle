<?php

/**
 * This file is part of the contentful/contentful-bundle package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\ContentfulBundle\Command\Delivery;

use Contentful\Core\Resource\ResourceArray;
use Contentful\Delivery\Client;
use Contentful\Delivery\Query;
use Contentful\Delivery\Resource\ContentType;
use Contentful\Delivery\Resource\Locale;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DebugCommand extends Command
{
    protected static $defaultName = 'contentful:delivery:debug';

    /**
     * @var Client[]
     */
    private $clients;

    /**
     * DebugCommand constructor.
     *
     * @param Client[] $clients        This is actually a Generator, but it behaves as an array of Client objects
     * @param array    $configurations
     */
    public function __construct($clients, $configurations = [])
    {
        parent::__construct(self::$defaultName);

        $availableNames = \array_keys($configurations);
        foreach ($clients as $index => $client) {
            $name = \array_shift($availableNames);
            $this->clients[$name] = $client;
        }

        $this->setDescription('Shows information about data coming from a certain client');
        $this->addArgument(
            'client-name',
            \count($this->clients) > 1 ? InputArgument::REQUIRED : InputArgument::OPTIONAL,
            'The name of the client to use'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('client-name');
        if (!$name && 1 === \count($this->clients)) {
            $name = \array_keys($this->clients)[0];
        }

        if (!isset($this->clients[$name])) {
            throw new InvalidArgumentException(\sprintf('Could not find the requested client "%s", use "contentful:delivery:info" to check the configured clients.', $name));
        }

        $client = $this->clients[$name];

        try {
            $space = $client->getSpace();
            $environment = $client->getEnvironment();
            $query = (new Query())
                ->setLimit(1000)
            ;
            /** @var ContentType[]|ResourceArray $contentTypes */
            $contentTypes = $client->getContentTypes($query);
            $entries = [];
            foreach ($contentTypes as $contentType) {
                $query = (new Query())
                    ->setLimit(1)
                    ->setContentType($contentType->getId())
                ;
                $entries[$contentType->getId()] = $client->getEntries($query)->getTotal();
            }
        } catch (\Exception $exception) {
            throw new RuntimeException('Requested service was found, but data could not be loaded. Try checking client credentials.', 0, $exception);
        }

        $io->title('Debug client');
        $io->text('Full service ID: contentful.delivery.'.$name.'_client');

        $io->section('Space');
        $io->text($space->getName());
        $io->comment('https://app.contentful.com/spaces/'.$space->getId().'/environments/'.$environment->getId());

        $io->section(\sprintf(
            'Locales (%d)',
            \count($environment->getLocales())
        ));
        $data = \array_map(function (Locale $locale) {
            return [
                $locale->getId(),
                $locale->getName(),
                $locale->getCode(),
                $locale->getFallbackCode(),
            ];
        }, $environment->getLocales());
        $io->table(
            ['ID', 'Name', 'Code', 'Fallback Code'],
            $data
        );
        $io->comment('https://app.contentful.com/spaces/'.$space->getId().'/environments/'.$environment->getId().'/locales');

        $io->section(\sprintf(
            'Content types (%d)',
            \count($contentTypes)
        ));
        $data = \array_map(function (ContentType $contentType) use ($entries) {
            return [
                $contentType->getId(),
                $contentType->getName(),
                \count($contentType->getFields()),
                $entries[$contentType->getId()],
                $contentType->getDescription(),
            ];
        }, $contentTypes->getItems());
        $io->table(
            ['ID', 'Name', 'Fields', 'Entries', 'Description'],
            $data
        );
        $io->comment('https://app.contentful.com/spaces/'.$space->getId().'/environments/'.$environment->getId().'/content_types');
    }
}
