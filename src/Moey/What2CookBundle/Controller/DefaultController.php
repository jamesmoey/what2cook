<?php

namespace Moey\What2CookBundle\Controller;

use Moey\What2CookBundle\Form\Type\RecipeIngredientUploadType;
use Moey\What2CookBundle\Services\RecipeFinder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $builder = $this->get("moey_what2cook.recipe_builder");
        $form = $this->createForm(
            new RecipeIngredientUploadType(),
            [],
            ['recipeItemBuilder' => $builder]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();
            /** @var UploadedFile $itemFile */
            $itemFile = $formData['items'];
            /** @var UploadedFile $recipeFile */
            $recipeFile = $formData['recipes'];
            $recipeFinder = new RecipeFinder(
                $builder->buildRecipe(
                    file_get_contents($recipeFile->getPath().DIRECTORY_SEPARATOR.$recipeFile->getFilename())
                ),
                $builder->buildItem(
                    file_get_contents($itemFile->getPath().DIRECTORY_SEPARATOR.$itemFile->getFilename())
                )
            );
            $recipe = $recipeFinder->findRecipe();
            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->set("recipe", ['name' => $recipe->getName()]);

            return $this->redirect($this->generateUrl("moey_what2cook_default_index"));
        }

        return ['form' => $form->createView()];
    }
}
