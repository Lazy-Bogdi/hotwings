<?php
// src/Command/ImportRecipesCommand.php

namespace App\Command\Scraper;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Interface\DataManager\DataManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Factory\ModelFactory;

#[AsCommand(
    name: 'app:import-recipes',
    description: 'Imports recipes from a JSON file into the database.',
    hidden: false,
    aliases: ['app:import-recipes']
)]
class ImportRecipesCommand extends Command
{
    // private $entityManager;
    private $dataDirectory;
    private $dataManager;
    private $modelFactory;

    public function __construct(DataManagerInterface $dataManager, string $dataDirectory, ModelFactory $modelFactory)
    {
        parent::__construct();
        $this->dataManager = $dataManager;
        $this->dataDirectory = $dataDirectory;
        $this->modelFactory = $modelFactory;
    }

    protected function configure()
    {
        $this
            ->setHelp('This command allows you to import recipes from a JSON file into the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $this->dataDirectory . '/recipes.json';
        $filesystem = new Filesystem();


        if (!$filesystem->exists($filePath)) {
            $output->writeln('No recipes file found.');
            return Command::FAILURE;
        }

        $jsonContent = file_get_contents($filePath);
        $recipesData = json_decode($jsonContent, true);

        if (null === $recipesData) {
            $output->writeln('Error decoding JSON.');
            return Command::FAILURE;
        }

        $progressBar = new ProgressBar($output, count($recipesData));
        $progressBar->start();

        foreach ($recipesData as $data) {
            $recipe = $this->modelFactory->create('recipe');
            $recipe->setTitle($data['title']);

            if ($this->dataManager->exists($recipe)) {
                $output->writeln("Skipping duplicate recipe: " . $data['title']);
                $progressBar->advance();
                continue;
            }

            foreach ($data['ingredients'] as $ingredientData) {
                $ingredient = $this->modelFactory->create('ingredient');
                $ingredient->setContent($ingredientData['content']);
                $recipe->addIngredient($ingredient);
            }

            $stepsAmount = count($data['steps']);
            $i = 1;
            foreach ($data['steps'] as $stepData) {
                $step = $this->modelFactory->create('recipeStep');
                // $step = new RecipeStep();
                $step->setStepNumber($i);
                $step->setTotalSteps($stepsAmount);
                $step->setContent($stepData['content']);
                $recipe->addStep($step);
                $i++;
            }

            $this->dataManager->save($recipe);
            $progressBar->advance();
        }

        // $this->dataManager->flush();
        $progressBar->finish();

        $output->writeln("\nImported recipes successfully.");

        return Command::SUCCESS;
    }
}
