<?php include(__DIR__ . '/../_partials/header.php'); ?>

    <section class="column stack-l color-scheme" id="colorscheme">
        <h1>Color Scheme</h1>
        <hr>
        <h2>Brand colors</h2>
        <?php
        $i = 0;
        ?>
        <div class="row gutter">
            <div class="column">
                <div class="color-block box color-primary">
                    <div class="bg-primary inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$paint-primary</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="color-block box">
                    <div class="bg-secondary inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$paint-secondary</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="color-block box">
                    <div class="bg-tertiary inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$paint-tertiary</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
        </div>

        <h2>Typography colors</h2>
        <div class="row gutter">
            <div class="column">
                <div class="color-block box">
                    <div class="bg-heading inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$heading</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="color-block box">
                    <div class="bg-body inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$body</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="color-block box">
                    <div class="bg-border inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$border</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
        </div>

        <h2>Neutral colors</h2>
        <div class="row gutter">
            <div class="column">
                <div class="color-block box">
                    <div class="bg-subtle inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$subtle</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="color-block box">
                    <div class="bg-dark inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$dark</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="color-block box">
                    <div class="bg-white inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$white</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="color-block box">
                    <div class="bg-black inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$black</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
        </div>

        <h2>Message colors</h2>
        <div class="row gutter">
            <div class="column">
                <div class="color-block box">
                    <div class="bg-success inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$succes</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="color-block box">
                    <div class="bg-warning inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$warning</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="color-block box">
                    <div class="bg-error inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$error</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="color-block box">
                    <div class="bg-information inset-l"></div>
                    <div class="content inset-s">
                        <label class="font-xs subtle">SCSS</label>
                        <div>$information</div>
                        <label class="font-xs subtle">HEX</label>
                        <div class="hexCode-<?php echo $i ?>"></div>
                        <label class="font-xs subtle">RGB</label>
                        <div class="rgbCode-<?php echo $i++ ?>"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include(__DIR__ . '/../_partials/footer.php'); ?>