<?php
// src/Command/ImportRecipesCommand.php

namespace App\Command;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\RecipeStep;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Helper\ProgressBar;

#[AsCommand(
    name: 'app:import-recipes',
    description: 'Imports recipes from a JSON file into the database.',
    hidden: false,
    aliases: ['app:import-recipes']
)]
class ImportRecipesCommand extends Command
{
    private $entityManager;
    private $dataDirectory;

    public function __construct(EntityManagerInterface $entityManager, string $dataDirectory)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->dataDirectory = $dataDirectory;
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
            $recipe = new Recipe();
            $recipe->setTitle($data['title']);

            
            foreach ($data['ingredients'] as $ingredientData) {
                $ingredient = new Ingredient();
                $ingredient->setContent($ingredientData['content']);
                $recipe->addIngredient($ingredient);
            }

            $stepsAmount = count($data['steps']);
            $i = 1;
            foreach ($data['steps'] as $stepData) {
                $step = new RecipeStep();
                $step->setStepNumber($i);
                $step->setTotalSteps($stepsAmount);
                $step->setContent($stepData['content']);
                $recipe->addStep($step);
                $i++;
            }

            $this->entityManager->persist($recipe);
            $progressBar->advance();
        }

        $this->entityManager->flush();
        $progressBar->finish();

        $output->writeln("\nImported recipes successfully.");

        return Command::SUCCESS;
    }
}
