<?php
namespace Moey\What2CookBundle\Form\Type;

use Moey\What2CookBundle\Services\RecipeItemBuilder;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CallbackValidator;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ExecutionContext;

class RecipeIngredientUploadType extends AbstractType {

    /** @var RecipeItemBuilder $recipeItemBuilder */
    protected $recipeItemBuilder;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->recipeItemBuilder = $options['recipeItemBuilder'];
        $builder
            ->add("items", "file", [
                'label'=>"Upload Items",
                'required'=>true,
                'constraints' => [
                    new NotNull(),
                    new File(),
                    new Callback([
                        'callback'=>[$this, 'validateItemFile']
                    ]),
                ]
            ])
            ->add("recipes", "file", [
                'label'=>'Upload Recipes',
                'required'=>true,
                'constraints' => [
                    new NotNull(),
                    new File(),
                    new Callback([
                        'callback'=>[$this, 'validateRecipeFile']
                    ]),
                ]
            ])
            ->add("find", "submit", [
                'label'=>'Find Recipe'
            ])
        ;
    }

    public function validateItemFile(UploadedFile $file, ExecutionContext $context) {
        try {
            $this->recipeItemBuilder->buildItem(file_get_contents($file->getPath().DIRECTORY_SEPARATOR.$file->getFilename()));
        } catch (\Exception $e) {
            $context->addViolation($e->getMessage());
        }
    }

    public function validateRecipeFile(UploadedFile $file, ExecutionContext $context) {
        try {
            $this->recipeItemBuilder->buildRecipe(file_get_contents($file->getPath().DIRECTORY_SEPARATOR.$file->getFilename()));
        } catch (\Exception $e) {
            $context->addViolation($e->getMessage());
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired([
                'recipeItemBuilder'
            ])
            ->setAllowedTypes([
                'recipeItemBuilder' => 'Moey\What2CookBundle\Services\RecipeItemBuilder'
            ]);
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'recipe_upload';
    }
}