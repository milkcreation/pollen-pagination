<?php

declare(strict_types=1);

namespace Pollen\Pagination\Adapters;

use Pollen\Pagination\PaginatorInterface;
use Pollen\Pagination\Partial\PaginationPartial;
use WP_Query;

class WpPaginationPartial extends PaginationPartial
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        global $wp_query;

        return array_merge(parent::defaultParams(), [
            /**
             * @var array|PaginatorInterface|object $query
             */
            'paginator' => $wp_query,
        ]);
    }

    /**
     * @inheritDoc
     *
     * @return PaginatorInterface|WpQueryPaginatorInterface
     */
    public function paginator(): PaginatorInterface
    {
        if ($this->paginator === null) {
            $paginator = $this->get('paginator');

            if ($paginator instanceof WP_Query) {
                $this->paginator = new WpQueryPaginator($paginator);
            } else {
                $this->paginator = parent::paginator();
            }
        }

        return $this->paginator;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        $this->parseUrl();

        if (!$this->has('url.segment')) {
            $this->paginator()->setSegmenting();
        }

        $this->parseLinks();

        if ($this->get('links.numbers')) {
            $this->parseNumbers();
        }

        return parent::render();
    }
}