<?php
/* @var string $content */
/* @var \Template\PhpRenderer $this */

$this->params['title'] = 'About';
$this->extend('layout/columns');
?>

<?php $this->beginBlock() ?>

<ul class="list-group mb-3">
    <h6>Site navigation</h6>
    <li class="list-group-item">
        <div>
            <h6 class="my-0">Cabinet navigation</h6>
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

<?php $this->endBlock('sidebar'); ?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">About</li>
        </ol>
    </nav>
</div>
<div class="container">
    <h1>About</h1>
    <p><?= htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE) ?>!</p>
</div>`
