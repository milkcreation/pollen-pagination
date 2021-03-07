<?php

declare(strict_types=1);

namespace Pollen\Pagination;

use Psr\Container\ContainerInterface as Container;
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
            $container = method_exists($this, 'getContainer') ? $this->getContainer() : null;

            if ($container instanceof Container && $container->has(PaginationManagerInterface::class)) {
                $this->paginationManager = $container->get(PaginationManagerInterface::class);
            } else {
                try {
                    $this->paginationManager = PaginationManager::getInstance();
                } catch (RuntimeException $e) {
                    $this->paginationManager = new PaginationManager();
                }
            }
        }

        return $this->paginationManager;
    }

    /**
     * Définition du gestionnaire de pagination.
     *
     * @param PaginationManagerInterface $pagination
     *
     * @return static
     */
    public function setPaginationManager(PaginationManagerInterface $pagination): self
    {
        $this->paginationManager = $pagination;

        return $this;
    }
}