<?php
/* @var string $content */
/* @var \Template\PhpRenderer $this */

$this->params['title'] = 'About';
$this->extend('layout/default');
?>

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
