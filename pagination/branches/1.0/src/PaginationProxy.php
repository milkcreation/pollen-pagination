<?php

declare(strict_types=1);

namespace Pollen\Pagination;

use Pollen\Support\ProxyResolver;
use RuntimeException;

trait PaginationProxy
{
    /**
     * Instance du gestionnaire de pagination.
     * @var PaginationManagerInterface
     */
    private $paginationManager;

    /**
     * Instance du gestionnaire de pagination.
     *
     * @return PaginationManagerInterface
     */
    public function pagination(): PaginationManagerInterface
    {
        if ($this->paginationManager === null) {
            try {
                $this->paginationManager = PaginationManager::getInstance();
            } catch (RuntimeException $e) {
                $this->paginationManager = ProxyResolver::getInstance(
                    PaginationManagerInterface::class,
                    PaginationManager::class,
                    method_exists($this, 'getContainer') ? $this->getContainer() : null
                );
            }
        }

        return $this->paginationManager;
    }

    /**
     * Définition du gestionnaire de pagination.
     *
     * @param PaginationManagerInterface $pagination
     *
     * @return void
     */
    public function setPaginationManager(PaginationManagerInterface $pagination): void
    {
        $this->paginationManager = $pagination;
    }
}