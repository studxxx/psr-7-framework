<?php
/* @var \Template\Php\PhpRenderer $this */
/* @var \App\ReadModel\Views\PostView $post */

$this->extend('layout/default');
?>

<?php $this->beginBlock('title') ?><?= $this->encode($post->title)?><?php $this->endBlock(); ?>

<?php $this->beginBlock('meta');?>
<meta name="description" content="About psr framework">
<?php $this->endBlock() ?>

<?php $this->beginBlock('breadcrumbs'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= $this->encode($this->path('home')) ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= $this->encode($this->path('blog')) ?>">Blog</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->encode($post->title)?></li>
    </ol>
</nav>
<?php $this->endBlock(); ?>

<?php $this->beginBlock('content'); ?>
<div class="container">
    <h1><?= $this->encode($post->title)?></h1>

    <div class="panel panel-default">
        <div class="panel-heading"><span class="pull-right"><?= $post->date->format('Y-m-d') ?></span></div>
        <div class="panel-body"><?= nl2br($this->encode($post->content)) ?></div>
    </div>
</div>
<?php $this->endBlock(); ?>
