<?php
namespace App\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;

class SignupForm extends FormModel
{
    private string $username = '';
    private string $password = '';
    private string $repeatPassword = '';
    
    public function getUsername(): string
    {
        return $this->username;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }
    
    public function getRepeatPassword(): string
    {
        return $this->repeatPassword;
    }
    
    public function attributeLabels(): array
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
            'repeatPassword' => 'Repeat Password'
        ];
    }
    
    public function getRules(): array
    {
        return [
            'username' => [Required::rule()],
            'password' => [Required::rule()],
            'repeatPassword' => [Required::rule()]
        ];
    }
    
}