<?php
/* @var string $name */
/* @var \Template\PhpRenderer $this */

$this->params['title'] = 'Cabinet';
$this->extend('layout/columns');
?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cabinet</li>
        </ol>
    </nav>
</div>
<div class="container">
    <h1>Cabinet of <?= htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE) ?>!</h1>
</div>
