<?php declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;

/**
 * @Entity(
 *     table = "contatticonpreferitiyii3"
 * )
 * 
 */
class Contatto
{
    /**
     * @Column(type="primary")
     * @var int
     */
    public $id;
    
    /**
     * @Column(type="string")
     * @var string
     */
    public $nome;
    
    /**
     * @Column(type="string")
     * @var string
     */
    public $cognome;
    
    /**
     * @Column(type="string")
     * @var string
     */
    public $telefono;
    
    /**
     * @Column(type="string")
     * @var string
     */
    public $indirizzo;
    
    /**
     * @Column(type="int")
     * @var int
     */
    public $preferito;
    
    /**
     * @Column(type="int")
     * @var int
     */
    public $user_id;
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getNome(): string
    {
        return $this->nome;
    }
    
    public function getCognome() {
        return $this->cognome;
    }
    
    public function getTelefono() {
        return $this->telefono;
    }
    
    public function getIndirizzo() {
        return $this->indirizzo;
    }

}