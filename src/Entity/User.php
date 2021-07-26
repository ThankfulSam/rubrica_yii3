<?php declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;

/**
 * @Entity 
 */
class User
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
    protected $username;
    
    /**
     * @Column(type="string")
     * @var string
     */
    protected $password;
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getUsername(): string
    {
        return $this->username;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
    
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}