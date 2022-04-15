<?php

declare(strict_types=1);

namespace App;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

// This table does not specify charset and collation and thus will fall back to the connection table defaults (utf8mb4_unicode_ci)
#[Entity]
#[Table(name: 'tags')]
final class Tag
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;

    // This always produces differences because it's the same charset the inherited connection table default.
    #[Column(type: 'string', options: ['charset' => 'utf8mb4', 'collation' => 'utf8mb4_bin'])]
    private string $name;

    // This always produces differences because it's the same charset and collation as the inherited connection table default.
    #[Column(type: 'string', options: ['charset' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci'])]
    private string $title;

    // This is fine, as it's totally different
    #[Column(type: 'string', options: ['charset' => 'ascii', 'collation' => 'ascii_general_ci'])]
    private string $description;
}
