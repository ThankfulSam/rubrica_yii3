<?php
namespace App\Reader;

use Yiisoft\Data\Reader\ReadableDataInterface;
use Spiral\Database\DatabaseManager;
use Yiisoft\Data\Reader\OffsetableDataInterface;
use Yiisoft\Data\Reader\CountableDataInterface;
use Yiisoft\User\CurrentUser;


class MyDataReader implements ReadableDataInterface, OffsetableDataInterface, CountableDataInterface
{
    
    public DatabaseManager $dbal;
    public int $limit;
    public int $offset;
    private CurrentUser $user;
    
    public function __construct(DatabaseManager $dbal, CurrentUser $user)
    {
        $this->dbal = $dbal;
        $this->user = $user;
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
            ->from('contatticonpreferitiyii3')
            ->offset($this->offset)
            ->limit($this->limit)
            ->where('user_id', (int)$this->user->getId())
            ->orderBy('nome')
            ->fetchAll();
        
        return $tab_contatti;
    }

    public function readOne()
    {
        $tab_contatti = $this->dbal->database('default')
        ->select()
        ->from('contatticonpreferitiyii3')
        ->offset($this->offset)
        ->where('user_id', (int)$this->user->getId())
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
        $num_entry = $this->dbal->database('default')->table('contatticonpreferitiyii3')->where('user_id', (int)$this->user->getId())->count();
        return $num_entry;
    }

}
