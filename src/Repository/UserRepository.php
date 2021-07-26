<?php
namespace App\Repository;

use Cycle\ORM\Select\Repository;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\Cycle\Data\Reader\EntityReader;

class UserRepository extends Repository
{
    
    private CurrentUser $user;
    /**
     * {@inheritDoc}
     * @see \Cycle\ORM\Select\Repository::__construct()
     */
    public function __construct(\Cycle\ORM\Select $select, CurrentUser $user)
    {
        parent::__construct($select);
        $this->user = $user;
    }
    
    public function all(): DataReaderInterface
    {
        return new EntityReader($this->select());
    }
    
    //messo di qua perch� il with filter sull'entity reader sembra possa essere
    //fatto una volta volta sola -> forse andava usato filter processors per fare pi� query
    public function search(string $nome, string $cognome) {
        return new EntityReader($this->select()
            ->where('user_id', $this->user->getId())
            ->andWhere('nome', 'like', '%'.$nome.'%')
            ->andWhere('cognome', 'like', '%'.$cognome.'%'));
    }
}

