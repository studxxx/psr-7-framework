<?php
/* @var \Template\PhpRenderer $this */
/* @var string $content */

$this->extend('layout/default');
?>

<?php $this->beginBlock('title') ?>404 - Not found<?php $this->endBlock(); ?>

<?php $this->beginBlock('breadcrumbs'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= $this->encode($this->path('home')) ?>">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Error</li>
    </ol>
</nav>
<?php $this->endBlock(); ?>

<?php $this->beginBlock('content'); ?>
<div class="container">
    <h1>404 - Not Found</h1>
</div>
<?php $this->endBlock(); ?>
