<?php

namespace Becklyn\OrderedFormBundle\Form\ResolvedType;

use Becklyn\OrderedFormBundle\Form\Builder\OrderedButtonBuilder;
use Becklyn\OrderedFormBundle\Form\Builder\OrderedFormBuilder;
use Becklyn\OrderedFormBundle\Sorter\FormFieldSorter;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\ButtonTypeInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ResolvedFormType;
use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\Form\SubmitButtonBuilder;
use Symfony\Component\Form\SubmitButtonTypeInterface;


class OrderedResolvedFormType extends ResolvedFormType
{
    /**
     * @inheritdoc
     */
    public function __construct (
        FormTypeInterface $innerType,
        array $typeExtensions = [],
        ResolvedFormTypeInterface $parent = null
    )
    {
        parent::__construct($innerType, $typeExtensions, $parent);
    }


    /**
     * @inheritDoc
     */
    public function finishView (FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

        $unorderedChildren = $view->children;
        $view->children = [];
        $sorter = $this->buildSorter($form);

        // add sorted fields
        foreach ($sorter->getSortedFieldNames() as $fieldName)
        {
            if (isset($unorderedChildren[$fieldName]))
            {
                $view->children[$fieldName] = $unorderedChildren[$fieldName];
            }
        }

        // add remaining missing fields (like _token, that doesn't end up in the form itself)
        foreach ($unorderedChildren as $fieldName => $child)
        {
            if (!isset($view->children[$fieldName]))
            {
                $view->children[$fieldName] = $unorderedChildren[$fieldName];
            }
        }
    }



    /**
     * Builds and returns the sorter
     *
     * @param FormInterface $form
     * @return FormFieldSorter
     * @throws \Becklyn\OrderedFormBundle\Exception\OrderedFormException
     */
    private function buildSorter (FormInterface $form)
    {
        $sorter = new FormFieldSorter();

        /** @var FormInterface $child */
        foreach ($form as $child)
        {
            $sorter->add($child->getName(), $child->getConfig()->getOption("position"));
        }

        return $sorter;
    }

}
