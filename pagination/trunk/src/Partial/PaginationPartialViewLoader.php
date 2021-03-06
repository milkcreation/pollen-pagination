<?php

declare(strict_types=1);

namespace Pollen\Pagination\Partial;

use Pollen\Partial\PartialViewLoader;
use RuntimeException;

class PaginationPartialViewLoader extends PartialViewLoader
{
    /**
     * Récupération de l'instance de délégation.
     *
     * @return PaginationPartialDriverInterface
     */
    protected function getDelegate(): PaginationPartialDriverInterface
    {
        /** @var PaginationPartialDriverInterface|object|null $delegate */
        $delegate = $this->engine->getDelegate();
        if ($delegate instanceof PaginationPartialDriverInterface) {
            return $delegate;
        }

        throw new RuntimeException('ViewLoader must have a delegate PaginationPartial Driver instance');
    }

    /**
     * Récupération de la page courante.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->getDelegate()->paginator()->getCurrentPage();
    }

    /**
     * Récupération de la dernière page.
     *
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->getDelegate()->paginator()->getLastPage();
    }
}