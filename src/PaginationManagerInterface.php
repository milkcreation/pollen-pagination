<?php

declare(strict_types=1);

namespace Pollen\Pagination;

use Pollen\Support\Concerns\BootableTraitInterface;
use Pollen\Support\Concerns\ConfigBagAwareTraitInterface;
use Pollen\Support\Proxy\ContainerProxyInterface;
use Pollen\Support\Proxy\PartialProxyInterface;

interface PaginationManagerInterface extends
    BootableTraitInterface,
    ConfigBagAwareTraitInterface,
    ContainerProxyInterface,
    PartialProxyInterface
{
    /**
     * Affichage de la pagination par défaut
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Chargement.
     *
     * @return static
     */
    public function boot(): PaginationManagerInterface;

    /**
     * Récupération de l'instance de l'adapteur.
     *
     * @return PaginationAdapterInterface|null
     */
    public function getAdapter(): ?PaginationAdapterInterface;

    /**
     * Récupération de l'instance du paginateur courant.
     *
     * @return PaginatorInterface|null
     */
    public function paginator(): ?PaginatorInterface;

    /**
     * Rendu d'affichage de la pagination.
     * @see \Pollen\Pagination\Partial\PaginationPartial
     *
     * @param array $args
     *
     * @return string
     */
    public function render(array $args = []): string;

    /**
     * Chemin absolu vers une ressource (fichier|répertoire).
     *
     * @param string|null $path Chemin relatif vers la ressource.
     *
     * @return string
     */
    public function resources(?string $path = null): string;

    /**
     * Définition du chemin absolu vers le répertoire des ressources.
     *
     * @param string $resourceBaseDir
     *
     * @return static
     */
    public function setResourcesBaseDir(string $resourceBaseDir): PaginationManagerInterface;

    /**
     * Définition de l'adapteur associé.
     *
     * @param PaginationAdapterInterface $adapter
     *
     * @return static
     */
    public function setAdapter(PaginationAdapterInterface $adapter): PaginationManagerInterface;

    /**
     * Définition de l'instance du paginateur courant.
     *
     * @param PaginatorInterface $paginator
     *
     * @return PaginationManagerInterface
     */
    public function setPaginator(PaginatorInterface $paginator): PaginationManagerInterface;
}