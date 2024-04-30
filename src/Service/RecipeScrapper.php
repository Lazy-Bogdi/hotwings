<?php
// src/Service/RecipeScraper.php

namespace App\Service;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class RecipeScraper
{
    private $browser;

    public function __construct()
    {
        $client = HttpClient::create([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
            ]
        ]);
        $this->browser = new HttpBrowser($client);
    }

    public function fetchRecipe($url): ?array
    {
        $crawler = $this->browser->request('GET', $url);

        // Debug: Check if the page was loaded correctly
        $status = $this->browser->getResponse()->getStatusCode();
        if ($status !== 200) {
            throw new \Exception("Failed to load the page, status code: $status");
        }

        // Assuming the first h1 is the title
        if (!$crawler->filter('h1')->count()) {
            throw new \Exception("No title found on the page");
        }

        // $title = $crawler->filter('h1')->first()->text();

        // $ingredients = $crawler->filter('ul.mntl-structured-ingredients__list li')->each(function (Crawler $node) {
        //     $quantity = $node->filter('span[data-ingredient-quantity="true"]')->text();
        //     $unit = $node->filter('span[data-ingredient-unit="true"]')->text();
        //     $name = $node->filter('span[data-ingredient-name="true"]')->text();
        //     return ['content' => trim("$quantity $unit $name")];
        // });
        // $steps = $crawler->filter('div.recipe__steps-content ol li')->each(function (Crawler $node) {
        //     $stepText = $node->filter('p')->text();
        //     return ['content' => trim($stepText)];
        // });

        // return [
        //     'title' => trim($title),
        //     'ingredients' => $ingredients,
        //     'steps' => $steps
        // ];

        $title = $crawler->filter('h1')->text();

        // Handle ingredients
        $ingredients = $crawler->filter('ul.mntl-structured-ingredients__list li')->each(function ($node) {
            return [
                'content' => trim($node->text())
            ];
        });

        // Handle steps
        $steps = $crawler->filter('div.recipe__steps-content ol li')->each(function ($node) {
            return [
                'content' => trim($node->text())
            ];
        });

        $recipe = null;
        if (!empty($ingredient) || !empty($steps)) {
            $recipe = [
                'title' => trim($title),
                'ingredients' => $ingredients,
                'steps' => $steps
            ];
        }

        return $recipe;
    }

    public function fetchAllRecipes($indexUrl): array
    {
        $crawler = $this->browser->request('GET', $indexUrl);
        $links = $crawler->filter('a.mntl-card-list-items')->each(function (Crawler $node) {
            return $node->attr('href');
        });

        $recipes = [];
        foreach ($links as $link) {
            $recipe = $this->fetchRecipe($link);
            if ($recipe) {
                $recipes[] = $recipe;
                // dump($recipe);
            }
            
        }
        return $recipes;
    }
}
