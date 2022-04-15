<?php

declare(strict_types=1);

namespace App;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'users', options: ['charset' => 'ascii', 'collation' => 'ascii_general_ci'])]
final class User
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;

    // This is an example where the charset and collation are the same as the table default.
    // It will always produce a difference.
    #[Column(type: 'string', options: ['charset' => 'ascii', 'collation' => 'ascii_general_ci'])]
    private string $firstName;

    // This is fine, as the charset and collation are different from the table default.
    #[Column(type: 'string', options: ['charset' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci'])]
    private string $lastName;

    // This is fine, as the charset and collation are different from the table default.
    #[Column(type: 'string', options: ['charset' => 'utf8mb4', 'collation' => 'utf8mb4_bin'])]
    private string $email;
}
