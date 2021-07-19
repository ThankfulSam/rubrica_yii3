<?php
namespace App\Reader;

use Yiisoft\Data\Reader\Filter\FilterInterface;

class MyFiltro implements FilterInterface
{
    private $field;
    
    public function __construct($field)
    {
        $this->field = $field;
    }
    public static function getOperator(): string
    {
        return 'my!2';
    }
    public function toArray(): array
    {
        return [static::getOperator(), $this->field];
    }
}