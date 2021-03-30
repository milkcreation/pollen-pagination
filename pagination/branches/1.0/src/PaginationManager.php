<?php

declare(strict_types=1);

namespace Pollen\Pagination;

use Pollen\Pagination\Adapters\WpPaginationAdapter;
use Pollen\Pagination\Partial\PaginationPartial;
use Pollen\Support\Concerns\BootableTrait;
use Pollen\Support\Concerns\ConfigBagAwareTrait;
use Pollen\Support\Filesystem;
use Pollen\Support\Proxy\ContainerProxy;
use Pollen\Support\Proxy\PartialProxy;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

class PaginationManager implements PaginationManagerInterface
{
    use BootableTrait;
    use ConfigBagAwareTrait;
    use ContainerProxy;
    use PartialProxy;

    /**
     * Instance principale.
     * @var static|null
     */
    private static $instance;

    /**
     * Instance de l'adapteur associé.
     * @var PaginationAdapterInterface
     */
    private $adapter;

    /**
     * Instance du paginateur courant.
     * @var PaginatorInterface|null
     */
    protected $paginator;

    /**
     * Chemin vers le répertoire des ressources.
     * @var string|null
     */
    protected $resourcesBaseDir;

    /**
     * @param array $config
     * @param Container|null $container
     *
     * @return void
     */
    public function __construct(array $config = [], ?Container $container = null)
    {
        $this->setConfig($config);

        if ($container !== null) {
            $this->setContainer($container);
        }

        if ($this->config('boot_enabled', true)) {
            $this->boot();
        }

        if (!self::$instance instanceof static) {
            self::$instance = $this;
        }
    }

    /**
     * Récupération de l'instance principale.
     *
     * @return static
     */
    public static function getInstance(): PaginationManagerInterface
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        throw new RuntimeException(sprintf('Unavailable [%s] instance', __CLASS__));
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @inheritDoc
     */
    public function boot(): PaginationManagerInterface
    {
        if (!$this->isBooted()) {
            $this->partial()->register(
                'pagination',
                $this->containerHas(PaginationPartial::class)
                    ? PaginationPartial::class
                    : new PaginationPartial($this, $this->partial())
            );

            if ($this->adapter === null && defined('WPINC')) {
                $this->setAdapter(new WpPaginationAdapter($this));
            }

            $this->setBooted();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAdapter(): ?PaginationAdapterInterface
    {
        return $this->adapter;
    }

    /**
     * @inheritDoc
     */
    public function paginator(): ?PaginatorInterface
    {
        return $this->paginator;
    }

    /**
     * @inheritDoc
     */
    public function render(array $args = []): string
    {
        return (string) $this->partial()->get('pagination', $args);
    }

    /**
     * @inheritDoc
     */
    public function resources(?string $path = null): string
    {
        if ($this->resourcesBaseDir === null) {
            $this->resourcesBaseDir = Filesystem::normalizePath(
                realpath(
                    dirname(__DIR__) . '/resources/'
                )
            );

            if (!file_exists($this->resourcesBaseDir)) {
                throw new RuntimeException('Outdated ressources directory unreachable');
            }
        }

        return is_null($path) ? $this->resourcesBaseDir : $this->resourcesBaseDir . Filesystem::normalizePath($path);
    }

    /**
     * @inheritDoc
     */
    public function setResourcesBaseDir(string $resourceBaseDir): PaginationManagerInterface
    {
        $this->resourcesBaseDir = Filesystem::normalizePath($resourceBaseDir);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setAdapter(PaginationAdapterInterface $adapter): PaginationManagerInterface
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPaginator(PaginatorInterface $paginator): PaginationManagerInterface
    {
        $this->paginator = $paginator;

        return $this;
    }
}