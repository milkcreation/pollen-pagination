<?php
/**
 * @var Pollen\Pagination\Partial\PaginationPartialViewLoader $this
 */
?>
<?php if ($this->getCurrentPage() < $this->getLastPage()) : ?>
    <li class="Pagination-item Pagination-item--last">
        <?php echo $this->partial('tag', $this->get('links.last')); ?>
    </li>
<?php endif;