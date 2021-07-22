<?php
namespace App\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

class SearchForm extends FormModel
{
    private string $nome = '';
    private string $cognome = '';
    
    public function getNome(): string
    {
        return $this->nome;
    }
    
    public function getCognome(): string
    {
        return $this->cognome;
    }
    
    public function attributeLabels(): array
    {
        return [
            'nome' => 'Nome',
            'cognome' => 'Cognome'
        ];
    }
    
    /*public function getRules(): array
    {
       
    }
    */
}