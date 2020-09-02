<?php
/* @var string $content */
/* @var \Template\PhpRenderer $this */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?= $this->renderBlock('meta') ?>
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">
    <title><?= $this->params['title'] ?> - App</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            padding-top: 70px;
        }
        .app {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .app-content {
            flex: 1;
        }
        .app-footer {
            padding-bottom: 1em;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="/">Application</a>
    <button class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault"
            aria-expanded="false"
            aria-label="Toggle navigation"
    >
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link fa fa-book" href="/about"> About</a></li>
            <li class="nav-item"><a class="nav-link fa fa-user" href="/cabinet"> Cabinet</a></li>
        </ul>
    </div>
</nav>

<main role="main" class="app-content">
    <?= $this->renderBlock('breadcrumbs') ?>
    <?= $content ?>
</main>

<footer class="app-footer">
    <div class="container">
        <hr>
        <p>&copy; My App <?= date('Y') ?></p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"
        integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf"
        crossorigin="anonymous"></script>
</body>
</html>
