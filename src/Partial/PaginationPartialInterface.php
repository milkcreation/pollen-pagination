<?php

declare(strict_types=1);

namespace Pollen\Pagination\Partial;

use Pollen\Pagination\PaginationProxyInterface;
use Pollen\Pagination\PaginatorInterface;
use Pollen\Partial\PartialDriverInterface;

interface PaginationPartialInterface extends PaginationProxyInterface, PartialDriverInterface
{
    /**
     * Récupération d'un séparateur de nombre.
     *
     * @param array $numbers Liste des numéros de page existants.
     *
     * @return void
     */
    public function ellipsis(array &$numbers): void;

    /**
     * Boucle de récupération des numéros de page.
     *
     * @param array $numbers Liste des numéros de page existants.
     * @param int $start Démarrage de la boucle de récupération.
     * @param int $end Fin de la boucle de récupération.
     *
     * @return void
     */
    public function numLoop(array &$numbers, int $start, int $end): void;

    /**
     * Récupération de l'instance du gestionnaire de requête de récupération des arguments de pagination.
     *
     * @return PaginatorInterface
     */
    public function paginator(): PaginatorInterface;

    /**
     * Traitement de la liste des liens.
     *
     * @return void
     */
    public function parseLinks(): void;

    /**
     * Traitement de la liste des numéros de page.
     *
     * @return void
     */
    public function parseNumbers(): void;

    /**
     * Traitement des arguments d'url.
     *
     * @return void
     */
    public function parseUrl(): void;
}