<?php
namespace App\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

class LoginForm extends FormModel
{
    private string $username = '';
    
    public function getUsername(): string
    {
        return $this->username;
    }
    
    public function attributeLabels(): array 
    {
        return [
            'username' => 'Username',
        ];
    }
    
    public function getRules(): array 
    {
        return [
            'username' => [Required::rule()]
        ];
    }
    
}
