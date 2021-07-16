<?php
namespace App\Reader;

use Yiisoft\Data\Reader\ReadableDataInterface;

class MyDataReader implements ReadableDataInterface
{
    
    public int $limit;
    
    public function __construct(){
        
    }

    public function withLimit(int $limit):self
    {
        $this->limit = $limit;
        //return $this;
    }

    public function read():iterable
    {}

    public function readOne()
    {}
    
}

