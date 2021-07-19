<?php
namespace App\Reader;

use Yiisoft\Data\Reader\ReadableDataInterface;
use Spiral\Database\DatabaseManager;
use Yiisoft\Data\Reader\OffsetableDataInterface;
use Yiisoft\Data\Reader\CountableDataInterface;


class MyDataReader implements ReadableDataInterface, OffsetableDataInterface, CountableDataInterface
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
        $result = clone $this;
        $result->limit = $limit;
        return $result;
    }

    public function read():iterable
    {
        $tab_contatti = $this->dbal->database('default')
            ->select()
            ->from('contatticonpreferiti')
            ->offset($this->offset)
            ->limit($this->limit)
            ->orderBy('nome')
            ->fetchAll();
        
        return $tab_contatti;
    }

    public function readOne()
    {
        $tab_contatti = $this->dbal->database('default')
        ->select()
        ->from('contatticonpreferiti')
        ->offset($this->offset)
        ->limit(1)
        ->fetchAll();
        
        return current($tab_contatti);
    }
    public function withOffset(int $offset): self
    {
        $result = clone $this;
        $result->offset = $offset;
        return $result;
    }

    public function count(): int
    {
        $num_entry = $this->dbal->database('default')->table('contatticonpreferiti')->count();
        return $num_entry;
    }

}
