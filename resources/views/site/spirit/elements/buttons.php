<?php include(__DIR__ . '/../_partials/header.php'); ?>

<section class="stack-l components" id="buttons">
    <h1>Buttons</h1>
    <hr>
    <h2>Standard</h2>
    <div class="stack" id="clone-27">
        <a class="btn btn-primary">Button primary</a>
        <a class="btn btn-primary-outline">Button secondary</a>
        <a class="btn btn-tertiary">Button tertiary</a>
    </div>
    <pre class="code-box" id="code-27"></pre>

    <div class="stack" id="clone-26">
        <a class="btn btn-o-primary">Button primary</a>
        <a class="btn btn-o-secondary">Button secondary</a>
        <a class="btn btn-o-tertiary">Button tertiary</a>
    </div>
    <pre class="code-box" id="code-26"></pre>



    <h2>Buttons styles</h2>
    <div class="stack" id="clone-25">
        <a class="btn btn-primary">Button default</a>
        <a class="btn btn-primary btn-round">Button round</a>
        <a class="btn btn-primary btn-circle"><i class="icon icon-heart"></i></a>
    </div>
    <pre class="code-box" id="code-25"></pre>


    <h2>Split Buttons</h2>
    <div class="stack inline-group" id="clone-24">
        <div class="btn-group">
            <button type="button" class="btn btn-primary">Save</button>
            <div class="dropdown">
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-primary">
                    <i class="icon icon-chevron-down"></i>
                    <div class="dropdown-menu">
                        <div><a href="#">As draft</a></div>
                        <div><a href="#">In review</a></div>
                    </div>
                </button>
            </div>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-o-primary">Save</button>
            <div class="dropdown">
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-o-primary">
                    <i class="icon icon-chevron-down"></i>
                    <div class="dropdown-menu">
                        <div><a href="#">As draft</a></div>
                        <div><a href="#">In review</a></div>
                    </div>
                </button>
            </div>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-tertiary">Save</button>
            <div class="dropdown">
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-tertiary">
                    <i class="icon icon-chevron-down"></i>
                    <div class="dropdown-menu">
                        <div><a href="#">As draft</a></div>
                        <div><a href="#">In review</a></div>
                    </div>
                </button>
            </div>
        </div>
    </div>
    <pre class="code-box" id="code-24"></pre>


    <h2>Buttons with an icon</h2>
    <div class="stack" id="clone-23">
        <a class="btn btn-primary btn-icon">
            <span class="">Volgende stap</span>
            <icon class="icon icon-arrow-right"></icon>
        </a>
        <a class="btn btn-primary-outline btn-icon">
            <icon class="icon icon-arrow-left "></icon>
            <span class="">Vorige stap</span>
        </a>
    </div>
    <div class="stack">
        <a class="btn btn-xl btn-o-primary">Button primary</a>
        <a class="btn btn-xl btn-o-secondary btn-round">Button round secondary</a>
    </div>
    <pre class="code-box" id="code-23"></pre>

    <h2>Link buttons</h2>
    <div class="stack" id="clone-22">
        <a class="btn btn-link text-primary">Button link</a>
        <a class="btn btn-link text-secondary">Button link</a>
        <a class="btn btn-link text-tertiary">Button link</a>
    </div>
    <pre class="code-box" id="code-22"></pre>

    <h2>Icon buttons</h2>
    <div class="stack" id="clone-20">
        <a class="btn btn-action btn-circle">
            <i class="icon icon-zap"></i>
        </a>
        <a class="btn btn-primary btn-action btn-circle">
            <i class="icon icon-eye"></i>
        </a>
        <a class="btn btn-primary-outline btn-action btn-circle">
            <i class="icon icon-zap"></i>
        </a>
        <a class="btn btn-warning btn-action btn-circle">
            <i class="icon icon-eye"></i>
        </a>
        <a class="btn btn-success btn-action btn-circle">
            <i class="icon icon-zap"></i>
        </a>
        <a class="btn btn-error btn-action btn-circle">
            <i class="icon icon-eye"></i>
        </a>
        <a class="btn btn-information btn-action btn-circle">
            <i class="icon icon-zap"></i>
        </a>
    </div>
</section>

<?php include(__DIR__ . '/../_partials/footer.php'); ?>
