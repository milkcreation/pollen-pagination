<?php

declare(strict_types=1);

namespace Pollen\Pagination\Adapters;

use Pollen\Pagination\PaginatorInterface;
use Pollen\Pagination\Partial\PaginationPartialDriver;
use WP_Query;

class WpPaginationPartialDriver extends PaginationPartialDriver
{
    /**
     * @inheritDoc
     *
     * @return PaginatorInterface|WpQueryPaginatorInterface
     */
    public function paginator(): PaginatorInterface
    {
        if ($this->paginator === null) {
            $paginator = $this->get('paginator');
            if ($paginator instanceof WP_Query || $paginator === null) {
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
    public function parseParams(): void
    {
        parent::parseParams();

        $this->parseUrl();
        if (!$this->has('url.segment')) {
            $this->paginator()->setSegmenting();
        }

        $this->parseLinks();

        if ($this->get('links.numbers')) {
            $this->parseNumbers();
        }
    }
}