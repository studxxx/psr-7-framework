<?php
/* @var \Template\Php\PhpRenderer $this */
/* @var string $content */

$this->extend('layout/default');
?>

<?php $this->beginBlock('title') ?>About<?php $this->endBlock(); ?>

<?php $this->beginBlock('meta');?>
<meta name="description" content="About psr framework">
<?php $this->endBlock() ?>

<?php $this->beginBlock('breadcrumbs'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= $this->encode($this->path('home')) ?>">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">About</li>
    </ol>
</nav>
<?php $this->endBlock(); ?>

<?php $this->beginBlock('content'); ?>
<div class="container">
    <h1>About</h1>
    <p><?= htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE) ?>!</p>
</div>
<?php $this->endBlock(); ?>
