<?php

declare(strict_types=1);

namespace Pollen\Pagination;

use Pollen\Support\Concerns\BootableTraitInterface;

interface PaginationAdapterInterface extends BootableTraitInterface, PaginationProxyInterface
{
    /**
     * Chargement.
     *
     * @return void
     */
    public function boot(): void;
}