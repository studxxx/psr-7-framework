<?php
/* @var \Template\Php\PhpRenderer $this */
/* @var \Throwable $e */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?= $this->renderBlock('meta') ?>
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">
    <title>Error</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<main role="main" class="app-content">
    <div class="container">
        <h1>Exception: <?= $this->encode($e->getMessage())?></h1>
        <p>Code: <?= $this->encode($e->getCode())?></p>
        <?php foreach ($e->getTrace() as $trace): ?>
            <p><?= $this->encode($trace['file'])?> on line <?= $this->encode($trace['line']) ?></p>
        <?php endforeach;?>
    </div>
</main>
</body>
</html>
