<?php
namespace App\Reader;

use Yiisoft\Data\Reader\ReadableDataInterface;
use Spiral\Database\DatabaseManager;
use Yiisoft\Data\Reader\OffsetableDataInterface;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Data\Reader\Filter\FilterInterface;
use Yiisoft\Data\Reader\Filter\FilterProcessorInterface;
use Yiisoft\Data\Reader\FilterableDataInterface;

class MyDataReader implements DataReaderInterface, FilterableDataInterface
{
    
    public DatabaseManager $dbal;
    public int $limit;
    public int $offset;
    
    public function __construct(DatabaseManager $dbal)
    {
        $this->dbal = $dbal;
    }

    public function withLimit(int $limit):self
    {
        $this->limit = $limit;
    }

    public function read():iterable
    {
        $tab_contatti = $this->dbal->database('default')->select()->from('contatticonpreferiti')->fetchAll();
        return $tab_contatti;
    }

    public function readOne()
    {}
    public function withOffset(int $offset): self
    {}
    public function getSort(): ?Sort
    {}

    public function getIterator()
    {}

    public function count(): int
    {
        $num_entry = $this->dbal->database('default')->table('contatticonpreferiti')->count();
        return $num_entry;
    }

    public function withSort(?Sort $sorting): self
    {}

    public function withFilterProcessors(FilterProcessorInterface ...$filterProcessors): self
    {}

    public function withFilter(FilterInterface $filter): self
    {}


    

}
