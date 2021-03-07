<?php

declare(strict_types=1);

namespace Pollen\Pagination;

interface PaginationProxyInterface
{
    /**
     * Instance du gestionnaire de pagination.
     *
     * @return PaginationManagerInterface
     */
    public function pagination(): PaginationManagerInterface;

    /**
     * Définition du gestionnaire de pagination.
     *
     * @param PaginationManagerInterface $pagination
     *
     * @return static
     */
    public function setPaginationManager(PaginationManagerInterface $pagination): self;
}