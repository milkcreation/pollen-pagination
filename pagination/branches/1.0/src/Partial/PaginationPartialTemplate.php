<?php

declare(strict_types=1);

namespace Pollen\Pagination\Partial;

use Pollen\Partial\PartialTemplate;
use RuntimeException;

class PaginationPartialTemplate extends PartialTemplate
{
    /**
     * Récupération de l'instance de délégation.
     *
     * @return PaginationPartialInterface
     */
    protected function getDelegate(): PaginationPartialInterface
    {
        /** @var PaginationPartialInterface|object|null $delegate */
        $delegate = $this->engine->getDelegate();
        if ($delegate instanceof PaginationPartialInterface) {
            return $delegate;
        }

        throw new RuntimeException('PaginationTemplate requires a delegate PaginationPartial instance');
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