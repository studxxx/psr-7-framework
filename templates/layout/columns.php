<?php
/* @var string $content */

$this->extends = 'layout/default';
?>

<div class="row">
    <div class="col-md-9">
        <?= $content ?>
    </div>
    <div class="col-md-3">
        <ul class="list-group mb-3">
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
    </div>
</div>
