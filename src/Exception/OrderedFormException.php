<?php declare(strict_types=1);

namespace Becklyn\OrderedFormBundle\Exception;

class OrderedFormException extends \Exception
{
    /**
     * @inheritdoc
     */
    public function __construct (string $message)
    {
        parent::__construct("Form `position`: {$message}", 0);
    }
}
