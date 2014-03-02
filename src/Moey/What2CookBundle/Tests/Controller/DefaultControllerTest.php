<?php

namespace Moey\What2CookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertTrue($crawler->filter('button:contains("Find Recipe")')->count() == 1);
    }

    public function testUploadItemNullFile() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton("Find Recipe")->form();
        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('span.help-block:contains("This value should not be null.")')->count() == 2);
    }

    public function testUploadItemInvalidFile() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton("Find Recipe")->form();
        $form['recipe_upload[items]']->upload(__DIR__.'/../sample/items.csv');
        $form['recipe_upload[recipes]']->upload(__DIR__.'/../sample/items.csv');
        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('html:contains("Recipes in Json can not be decoded")')->count() == 1);
    }

    public function testUploadItemInvalidRecipe() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton("Find Recipe")->form();
        $form['recipe_upload[items]']->upload(__DIR__.'/../sample/items.csv');
        $form['recipe_upload[recipes]']->upload(__DIR__.'/../sample/bad_recipes.json');
        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('html:contains("salad sandwich does not contain any ingredient")')->count() == 1);
    }

    public function testValidUploadItemAndRecipe() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton("Find Recipe")->form();
        $form['recipe_upload[items]']->upload(__DIR__.'/../sample/items.csv');
        $form['recipe_upload[recipes]']->upload(__DIR__.'/../sample/recipes.json');
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('html:contains("Grilled cheese on toast")')->count() == 1);
    }
}
