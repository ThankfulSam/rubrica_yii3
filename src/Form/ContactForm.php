<?php
namespace App\Form;

use Yiisoft\Form\FormModel;

class ContactForm extends FormModel
{
    private int $id;
    private string $nome;
    private string $cognome;
    private string $telefono;
    private string $indirizzo;
    private int $preferito;
    protected int $user_id;
    
    public function getId() {
        return $this->id;
    }
    
    public function getNome() {
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
    
    public function getUserId(){
        return $this->user_id;
    }
    
    public function setUserId(int $user_id){
        $this->user_id = $user_id;
    }
    
    public function loadData(array $array) {
        $this->id = $array[0];
        $this->nome = $array[1];
        $this->cognome = $array[2];
        $this->telefono = $array[3];
        $this->indirizzo = $array[4];
        $this->preferito = $array[5];
        $this->user_id = $array[6];
    }
    
    public function attributeLabels(): array
    {
        return [
            'id' => 'Id',
            'nome' => 'Nome',
            'cognome' => 'Cognome',
            'telefono' => 'Telefono',
            'indirizzo' => 'Indirizzo',
            'preferito' => 'Preferito'
        ];
    }
}
