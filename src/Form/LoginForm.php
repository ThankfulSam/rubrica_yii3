<?php
namespace App\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

class LoginForm extends FormModel
{
    private string $id = '';
    private string $password = '';
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }
    
    public function attributeLabels(): array 
    {
        return [
            'id' => 'Id',
            'password' => 'Password'
        ];
    }
    
    public function getRules(): array 
    {
        return [
            'id' => [Required::rule()],
            'password' => [Required::rule()],
        ];
    }
    
}
