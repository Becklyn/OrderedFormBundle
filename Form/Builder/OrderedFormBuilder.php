<?php

namespace Becklyn\OrderedFormBundle\Form\Builder;

use Symfony\Component\Form\FormBuilder;


class OrderedFormBuilder extends FormBuilder
{
    /**
     * @var string|array|null
     */
    private $position;

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        if ($this->locked) {
            throw new \BadMethodCallException('The config builder cannot be modified anymore.');
        }

        if (is_string($position) && ($position !== 'first') && ($position !== 'last')) {
            //throw OrderedConfigurationException::createInvalidStringPosition($this->getName(), $position);
            throw new \Exception("Invalid position");
        }

        if (is_array($position) && !isset($position['before']) && !isset($position['after'])) {
            //throw OrderedConfigurationException::createInvalidArrayPosition($this->getName(), $position);
            throw new \Exception("Invalid key");
        }

        $this->position = $position;

        return $this;
    }
}
