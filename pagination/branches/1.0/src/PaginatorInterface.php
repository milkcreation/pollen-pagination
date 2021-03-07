<?php

declare(strict_types=1);

namespace Pollen\Pagination;

use Psr\Http\Message\UriInterface;

interface PaginatorInterface
{
    /**
     * Récupération de l'url de base.
     *
     * @return UriInterface|null
     */
    public function getBaseUrl(): ?UriInterface;

    /**
     * Récupération du nombre d'éléments courant.
     *
     * @return int
     */
    public function getCount(): int;

    /**
     * Récupération de la page courante.
     *
     * @return int
     */
    public function getCurrentPage(): int;

    /**
     * Récupération de l'url vers la première page d'éléments.
     *
     * @return string
     */
    public function getFirstPageUrl(): string;

    /**
     * Récupération du numéro de la dernière page.
     *
     * @return int
     */
    public function getLastPage(): int;

    /**
     * Récupération de l'url vers la dernière page d'éléments.
     *
     * @return string
     */
    public function getLastPageUrl(): string;

    /**
     * Récupération du numéro de la page suivante d'éléments.
     *
     * @return int
     */
    public function getNextPage(): int;

    /**
     * Récupération de l'url vers la page suivante d'éléments.
     *
     * @return string
     */
    public function getNextPageUrl(): string;

    /**
     * Récupération de la ligne de démarrage du traitement.
     *
     * @return int
     */
    public function getOffset(): int;

    /**
     * Récupération de l'indice de qualification des pages.
     *
     * @return string
     */
    public function getPageIndex(): string;
    /**
     * Récupération de l'url associé à un numéro de page
     *
     * @param int $num
     *
     * @return string
     */
    public function getPageNumUrl(int $num): string;

    /**
     * Récupération du nombre d'élément par page.
     *
     * @return int|null
     */
    public function getPerPage(): ?int;

    /**
     * Récupération du numéro de la page précédente d'éléments.
     *
     * @return int
     */
    public function getPreviousPage(): int;

    /**
     * Récupération de l'url vers la page précédente d'éléments.
     *
     * @return string
     */
    public function getPreviousPageUrl(): string;

    /**
     * Récupération du nombre total d'éléments.
     *
     * @return int
     */
    public function getTotal(): int;

    /**
     * Vérifie si les urls de pagination sont basée sur des segments.
     *
     * @return bool
     */
    public function isSegmented(): bool;

    /**
     * Conversion de l'objet en élément serializable.
     *
     * @return array
     */
    public function jsonSerialize(): array;

    /**
     * Traitement de la liste des arguments
     *
     * @param array $args
     *
     * @return void
     */
    public function parseArgs(array $args): void;

    /**
     * Définition de l'url de base utilisé pour les liens de pagination.
     * {@internal %d représente le numéro de page.}
     *
     * @param string $baseUrl
     *
     * @return static
     */
    public function setBaseUrl(string $baseUrl): PaginatorInterface;

    /**
     * Définition du nombre d'éléments courants trouvés.
     *
     * @param int $count
     *
     * @return static
     */
    public function setCount(int $count): PaginatorInterface;

    /**
     * Définition de la page courante de récupération des éléments.
     *
     * @param int $page
     *
     * @return static
     */
    public function setCurrentPage(int $page): PaginatorInterface;

    /**
     * Définition du numéro de la dernière page.
     *
     * @param int $lastPage
     *
     * @return static
     */
    public function setLastPage(int $lastPage): PaginatorInterface;

    /**
     * Définition de la ligne de démarrage du traitement de récupération des éléments.
     *
     * @param int $offset
     *
     * @return static
     */
    public function setOffset(int $offset): PaginatorInterface;

    /**
     * Définition de l'indice de qualification des page.
     *
     * @param string $index
     *
     * @return static
     */
    public function setPageIndex(string $index = 'page'): PaginatorInterface;

    /**
     * Définition du nombre total d'éléments par page.
     *
     * @param int|null $perPage
     *
     * @return static
     */
    public function setPerPage(?int $perPage= null): PaginatorInterface;

    /**
     * Activation de l'url segmentée.
     *
     * @param bool $segmented
     *
     * @return static
     */
    public function setSegmenting(bool $segmented = true): PaginatorInterface;

    /**
     * Définition du nombre total d'éléments.
     *
     * @param int $total
     *
     * @return static
     */
    public function setTotal(int $total): PaginatorInterface;

    /**
     * Récupération d'une représentation de la classe sous la forme d'un tableau.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Conversion de l'instance de la classe sous la forme d'un JSON.
     *
     * @return string
     */
    public function toJson(): string;
}