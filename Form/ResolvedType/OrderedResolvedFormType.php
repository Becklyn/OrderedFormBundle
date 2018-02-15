<?php

namespace Becklyn\OrderedFormBundle\Form\ResolvedType;

use Becklyn\OrderedFormBundle\Form\Builder\OrderedFormBuilder;
use Becklyn\OrderedFormBundle\Sorter\FormFieldSorter;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ResolvedFormType;
use Symfony\Component\Form\ResolvedFormTypeInterface;


class OrderedResolvedFormType extends ResolvedFormType
{
    /**
     * @var FormFieldSorter
     */
    private $sorter;


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

        $children = $view->children;
        $view->children = [];
        $sorter = new FormFieldSorter($form);

        foreach ($sorter->getSorted() as $fieldName)
        {
            $view->children[$fieldName] = $children[$fieldName];
        }
    }


    /**
     * @inheritDoc
     */
    protected function newBuilder ($name, $dataClass, FormFactoryInterface $factory, array $options)
    {
        return new OrderedFormBuilder($name, $dataClass, new EventDispatcher(), $factory, $options);
    }


}
