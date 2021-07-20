<?php
namespace App\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

class LoginForm extends FormModel
{
    private string $username = '';
    private string $password = '';
    
    public function getUsername(): string
    {
        return $this->username;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }
    
    public function attributeLabels(): array 
    {
        return [
            'username' => 'Username',
            'password' => 'Password'
        ];
    }
    
    public function getRules(): array 
    {
        return [
            'username' => [Required::rule()],
            'password' => [Required::rule()],
        ];
    }
    
}