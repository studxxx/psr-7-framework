<?php
/* @var \Template\PhpRenderer $this */

$this->extend('layout/default');
?>

<?php $this->beginBlock('title') ?>Hello<?php $this->endBlock(); ?>

<?php $this->beginBlock('meta');?>
<meta name="description" content="Home page description">
<?php $this->endBlock() ?>

<?php $this->beginBlock('content'); ?>
<div class="jumbotron">
    <div class="container">
        <h1 class="display-3">Hello!</h1>
        <p>Congratulations! You have successfully created your application.</p>
    </div>
</div>
<?php $this->endBlock() ?>
