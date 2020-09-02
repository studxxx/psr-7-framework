<?php
/* @var \Template\PhpRenderer $this */

$this->extend('layout/default');
?>

<div class="row">
    <div class="col-md-9">
        <?= $this->renderBlock('main') ?>
    </div>
    <div class="col-md-3">
        <?php if ($this->ensureBlock('sidebar')) : ?>
            <ul class="list-group mb-3">
                <h6>Site navigation</h6>
                <li class="list-group-item">
                    <div>
                        <h6 class="my-0">Cabinet</h6>
                        <small class="text-muted">Cabinet description</small>
                    </div>
                </li>
            </ul>
        <?php $this->endBlock(); endif; ?>
        <?= $this->renderBlock('sidebar') ?>
    </div>
</div>
