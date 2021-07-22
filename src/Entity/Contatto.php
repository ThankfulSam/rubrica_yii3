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
    protected $id;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $nome;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $cognome;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $telefono;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $indirizzo;
    
    /**
     * @Column(type="int")
     * @var int
     */
    protected $preferito;
    
    /**
     * @Column(type="int")
     * @var int
     */
    protected $user_id;
    
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
    
    public function setPreferito(){
        if($this->preferito == 1){
            $this->preferito = 0;
        } else {
            $this->preferito = 1;  
        }
    }

}