<?php
/* @var string $name */
/* @var \Template\PhpRenderer $this */

$this->extend('layout/columns');
?>

<?php $this->beginBlock('title') ?>Cabinet<?php $this->endBlock(); ?>

<?php $this->beginBlock('breadcrumbs'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cabinet</li>
    </ol>
</nav>
<?php $this->endBlock(); ?>

<?php $this->beginBlock('main'); ?>
<div class="container">
    <h1>Cabinet of <?= htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE) ?>!</h1>
</div>
<?php $this->endBlock(); ?>

<?php $this->beginBlock('sidebar') ?>
<ul class="list-group mb-3">
    <h6>Cabinet navigation</h6>
    <li class="list-group-item">
        <div>
            <h6 class="my-0">Cabinet</h6>
            <small class="text-muted">Cabinet description</small>
        </div>
    </li>
    <li class="list-group-item">
        <div>
            <h6 class="my-0">Cabinet navigation</h6>
            <small class="text-muted">Navigation description</small>
        </div>
    </li>
</ul>
<?php $this->endBlock(); ?>
