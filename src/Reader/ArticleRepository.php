<?php
namespace src\Reader;

use Cycle\ORM\Select\Repository;
use Yiisoft\Data\Reader\DataReaderInterface;
use \Yiisoft\Yii\Cycle\Data\Reader\EntityReader;

class ArticleRepository extends Repository
{
    /**
     * @return EntityReader
     */
    public function findPublic(): DataReaderInterface
    {
        return new EntityReader($this->select()->where(['public' => true]));
    }
}

