<?php
// src/Command/ScrapeRecipesCommand.php

namespace App\Command;

use App\Service\RecipeScraper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

#[AsCommand(name: 'app:scrape-recipes')]
class ScrapeRecipesCommand extends Command
{
    // protected static $defaultName = 'app:scrape-recipes';

    private $scraper;
    private $dataDirectory;

    public function __construct(RecipeScraper $scraper, string $dataDirectory)
    {
        $this->scraper = $scraper;
        $this->dataDirectory = $dataDirectory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Scrapes recipes from a specified website.')
            ->setHelp('This command allows you to scrape recipes from websites and potentially save them directly to a database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting scrape...');
        $recipes = $this->scraper->fetchAllRecipes('https://www.allrecipes.com/recipes/17561/lunch/');
        $output->writeln('Scraped ' . count($recipes) . ' recipes.');

        if (count($recipes) > 0) {
            $filesystem = new Filesystem();
            try {
                $filePath = $this->dataDirectory . '/recipes.json';
                $filesystem->dumpFile($filePath, json_encode($recipes, JSON_PRETTY_PRINT));
                $output->writeln("Recipes saved to '$filePath'");
            } catch (IOExceptionInterface $exception) {
                $output->writeln("Error writing file at " . $exception->getPath());
                return Command::FAILURE;
            }
        }

        // Save to database or output data
        // $this->recipeService->saveRecipes($recipes); // Implement this method if needed

        return Command::SUCCESS;
    }
}
