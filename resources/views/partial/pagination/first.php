<?php
/**
 * @var Pollen\Pagination\Partial\PaginationPartialViewLoader $this
 */
?>
<?php if ($this->getCurrentPage() > 1) : ?>
    <li class="Pagination-item Pagination-item--first">
        <?php echo $this->partial('tag', $this->get('links.first')); ?>
    </li>
<?php endif;