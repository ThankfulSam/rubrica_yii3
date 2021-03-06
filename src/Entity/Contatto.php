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
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getNome()
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
    
    public function getPreferito() {
        return $this->preferito;
    }
    
    public function getUserId() {
        return $this->user_id;
    }
    
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }
    
    public function setCognome(string $cognome): void 
    {
        $this->cognome = $cognome;
    }
    
    public function setTelefono(string $telefono): void
    {
        $this->telefono = $telefono;
    }
    
    public function setIndirizzo(string $indirizzo): void
    {
        $this->indirizzo = $indirizzo;
    }
    
    public function setUserId(string $user_id): void 
    {
        $this->user_id = (int)$user_id;
    }
    
    public function setPreferito(?int $preferito): void 
    {
        
        if(isset($preferito)){
            $this->preferito = $preferito;
        } else {
            if ($this->preferito == 1){
                $this->preferito = 0;
            } else {
                $this->preferito = 1;
            }
        }
        
    }
    
    public function updateAll(string $nome, string $cognome, string $telefono, string $indirizzo){
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->telefono = $telefono;
        $this->indirizzo = $indirizzo;
    }

}