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

        // add remaining missing fields
        foreach ($unorderedChildren as $fieldName => $child)
        {
            if (!isset($view->children[$fieldName]))
            {
                $view->children[$fieldName] = $unorderedChildren[$fieldName];
            }
        }
    }


    /**
     * @inheritDoc
     */
    protected function newBuilder ($name, $dataClass, FormFactoryInterface $factory, array $options)
    {
        return new OrderedFormBuilder($name, $dataClass, new EventDispatcher(), $factory, $options);
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
            $sorter->add($child->getName(), $child->getConfig()->getPosition());
        }

        return $sorter;
    }

}
