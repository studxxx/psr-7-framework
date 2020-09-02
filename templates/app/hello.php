<?php
/* @var \Template\PhpRenderer $this */

$this->params['title'] = 'Hello';
$this->extend('layout/default');
?>

<?php $this->beginBlock('meta');?>
<meta name="description" content="Home page description">
<?php $this->endBlock() ?>

<div class="jumbotron">
    <div class="container">
        <h1 class="display-3">Hello!</h1>
        <p>Congratulations! You have successfully created your application.</p>
    </div>
</div>
