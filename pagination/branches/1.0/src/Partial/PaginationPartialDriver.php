<?php

declare(strict_types=1);

namespace Pollen\Pagination\Partial;

use Pollen\Pagination\Paginator;
use Pollen\Pagination\PaginatorInterface;
use Pollen\Partial\PartialDriver;

class PaginationPartialDriver extends PartialDriver implements PaginationPartialDriverInterface
{
    /**
     * Instance du gestionnaire de pagination.
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            /**
             * @var string|array|null $url Url de pagination {
             * @var string|null $base l'url peux contenir %d en remplacement du numéro de page. Si null, url courante.
             * @var bool $segment Activation des url de pagination basé sur la segmentation.
             * @var string $index Indice de qualification d'une page
             * }
             */
            'url'   => null,
            /**
             * @var array $links {
             * @var bool|array $first Activation du lien vers la première page|Liste d'attributs.
             * @var bool|array $last Activation du lien vers la dernière page|Liste d'attributs.
             * @var bool|array $previous Activation du lien vers la page précédente|Liste d'attributs.
             * @var bool|array $next Activation du lien vers la page suivante|Liste d'attributs.
             * @var bool|array $numbers Activation de l'affichage de la numérotation des pages|Liste d'attributs {
             * @var int $range
             * @var int $anchor
             * @var int $gap
             * }
             */
            'links' => [
                'first'    => true,
                'last'     => true,
                'previous' => true,
                'next'     => true,
                'numbers'  => true,
            ],
            /**
             * @var array|PaginatorInterface|object $query
             */
            'paginator' => null,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function ellipsis(array &$numbers): void
    {
        $numbers[] = [
            'tag'     => 'span',
            'content' => '...',
            'attrs'   => 'Pagination-itemEllipsis',
        ];
    }

    /**
     * @inheritDoc
     */
    public function numLoop(array &$numbers, int $start, int $end): void
    {
        for ($num = $start; $num <= $end; $num++) {
            if ($num === 1 && !$this->paginator()->getCurrentPage()) {
                $current = 'true';
            } elseif ($this->paginator()->getCurrentPage() === $num) {
                $current = 'true';
            } else {
                $current = 'false';
            }

            $numbers[] = [
                'tag'     => 'a',
                'content' => $num,
                'attrs'   => [
                    'class'        => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'         => $this->paginator()->getPageNumUrl($num),
                    'aria-current' => $current,
                ],
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function paginator(): PaginatorInterface
    {
        if ($this->paginator === null) {
            $paginator = $this->get('paginator');
            if ($paginator instanceof PaginatorInterface) {
                $this->paginator = $paginator;
            } elseif (is_array($paginator)) {
                $this->paginator = new Paginator();
            } elseif (is_object($paginator)) {
                $this->paginator = new Paginator(get_object_vars($paginator));
            } else {
                $this->paginator = new Paginator();
            }
        }

        return $this->paginator;
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): void
    {
        parent::parseParams();

        $this->parseUrl();
        $this->parseLinks();

        if ($this->get('links.numbers')) {
            $this->parseNumbers();
        }
    }

    /**
     * @inheritDoc
     */
    public function parseLinks(): void
    {
        $defaults = [
            'first'    => [
                'tag'     => 'a',
                'content' => '&laquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->paginator()->getPageNumUrl(1),
                ],
            ],
            'last'     => [
                'tag'     => 'a',
                'content' => '&raquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->paginator()->getPageNumUrl($this->paginator()->getLastPage()),
                ],
            ],
            'previous' => [
                'tag'     => 'a',
                'content' => '&lsaquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->paginator()->getPageNumUrl($this->paginator()->getCurrentPage() - 1),
                ],
            ],
            'next'     => [
                'tag'     => 'a',
                'content' => '&rsaquo;',
                'attrs'   => [
                    'class' => 'Pagination-itemPage Pagination-itemPage--link',
                    'href'  => $this->paginator()->getPageNumUrl($this->paginator()->getCurrentPage() + 1),
                ],
            ],
        ];

        foreach (array_keys($defaults) as $key) {
            $attrs = $this->get("links.{$key}", []);

            if ($attrs === false) {
                $attrs = [];
            } elseif ($attrs === true) {
                $attrs = $defaults[$key];
            } elseif (is_string($attrs)) {
                $attrs = array_merge($defaults[$key], ['content' => $attrs]);
            } else {
                $attrs = array_merge($defaults[$key], $attrs);
            }

            $this->set("links.{$key}", $attrs);
        }
    }

    /**
     * @inheritDoc
     */
    public function parseNumbers(): void
    {
        $range = (int) $this->get('links.numbers.range', 2);
        $anchor = (int)$this->get('links.numbers.anchor', 3);
        $gap = (int) $this->get('links.numbers.gap', 1);

        $min_links = ($range * 2) + 1;
        $block_min = min($this->paginator()->getCurrentPage() - $range, $this->paginator()->getLastPage() - $min_links);
        $block_high = max($this->paginator()->getCurrentPage() + $range, $min_links);

        $left_gap = ($block_min - $anchor - $gap) > 0;
        $right_gap = ($block_high + $anchor + $gap) < $this->paginator()->getLastPage();

        $numbers = [];
        if ($left_gap && !$right_gap) {
            $this->numLoop($numbers, 1, $anchor);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, $block_min, $this->paginator()->getLastPage());
        } elseif ($left_gap && $right_gap) {
            $this->numLoop($numbers, 1, $anchor);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, $block_min, $block_high);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, ($this->paginator()->getLastPage() - $anchor + 1), $this->paginator()->getLastPage());
        } elseif (!$left_gap && $right_gap) {
            $this->numLoop($numbers, 1, $block_high);
            $this->ellipsis($numbers);
            $this->numLoop($numbers, ($this->paginator()->getLastPage() - $anchor + 1), $this->paginator()->getLastPage());
        } else {
            $this->numLoop($numbers, 1, $this->paginator()->getLastPage());
        }

        $this->set('numbers', $numbers);
    }

    /**
     * @inheritDoc
     */
    public function parseUrl(): void
    {
        if ($this->has('url.base')) {
            $this->paginator()->setBaseUrl($this->get('url.base'));
        }

        if ($this->has('url.segment')) {
            $this->paginator()->setSegmenting($this->get('url.segment'));
        }

        if ($this->has('url.index')) {
            $this->paginator()->setPageIndex($this->get('url.index'));
        }

        if (!is_array($this->get('url'))) {
            $this->paginator()->setBaseUrl($this->get('url'));
        }
    }

    /**
     * @inheritDoc
     */
    public function view(?string $view = null, array $data = [])
    {
        if ($this->viewEngine === null) {
            $this->viewEngine = parent::view();

            $this->view()->setLoader(PaginationPartialViewLoader::class);
        }
        return parent::view($view, $data);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        return $this->partial()->resources('/views/pagination');
    }
}