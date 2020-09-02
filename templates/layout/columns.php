<?php
/* @var string $content */
/* @var \Template\PhpRenderer $this */

$this->extend('layout/default');
?>

<div class="row">
    <div class="col-md-9">
        <?= $content ?>
    </div>
    <div class="col-md-3">
        <?= $this->renderBlock('sidebar') ?>
    </div>
</div>
