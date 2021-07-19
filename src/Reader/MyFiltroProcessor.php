<?php
namespace App\Reader;

use Yiisoft\Data\Reader\Filter\FilterProcessorInterface;

class MyFiltroProcessor implements FilterProcessorInterface
{
    public function getOperator(): string
    {
        return MyFiltro::getOperator();
    }
    
    public function match(array $item, array $arguments, array $filterProcessors): bool
    {
        [$field] = $arguments;
        return $item[$field] != 2;
    }
}
