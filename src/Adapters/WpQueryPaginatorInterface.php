<?php

declare(strict_types=1);

namespace Pollen\Pagination\Adapters;

use Pollen\Pagination\PaginatorInterface;
use WP_Query;

interface WpQueryPaginatorInterface extends PaginatorInterface
{
    /**
     * Récupération des arguments de pagination depuis une requête WP_Query.
     *
     * @param WP_Query $wpQuery
     *
     * @return array
     */
    public function getWpQueryArgs(WP_Query $wpQuery): array;

    /**
     * Récupération de l'instance du générateur de requête
     *
     * @return WP_Query|null
     */
    public function getQueryBuilder(): ?object;
}