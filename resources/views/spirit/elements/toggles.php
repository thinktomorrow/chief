<?php include(__DIR__.'/../_partials/header.php'); ?>

    <section class="stack-l row" id="toggles">
        <h1>Toggles</h1>
        <hr>
        <!-- RADIO BUTTONS AND CHECKBOXES -->
        <div class="column-12">
            <h2>Checkboxes</h2>
            <div class="input-group stack row">
                <div  id="clone-11">
                    <label class="column-12 custom-indicators" for="check-one">
                        <input value="checkbox" id="check-one" type="checkbox">
                        <span class="custom-checkbox"></span>
                        Checkbox
                    </label>
                </div>
                <label class="column-12 custom-indicators" for="check-two">
                    <input value="checkbox" id="check-two" type="checkbox" checked>
                    <span class="custom-checkbox"></span>
                    Checkbox checked
                </label>
                <label class="column-12 custom-indicators disabled" for="check-three">
                    <input value="checkbox" id="check-three" type="checkbox" disabled>
                    <span class="custom-checkbox"></span>
                    Checkbox disabled
                </label>
                <label class="column-12 custom-indicators disabled" for="check-four">
                    <input value="checkbox" id="check-four" type="checkbox" disabled checked>
                    <span class="custom-checkbox"></span>
                    Checkbox checked and disabled
                </label>
            </div>
            <pre class="code-box" id="code-11"></pre>

        </div>
        <div class="column-12">
            <h2>Radio buttons</h2>
            <div class="input-group stack row">
                <div  id="clone-10">
                    <label class="column-12 custom-indicators" for="check-five">
                        <input value="radio" name="radio" id="check-five" type="radio">
                        <span class="custom-radiobutton"></span>
                        Radio
                    </label>
                </div>
                <label class="column-12 custom-indicators" for="check-six">
                    <input value="radio" name="radio" id="check-six" type="radio" checked>
                    <span class="custom-radiobutton"></span>
                    Radio selected
                </label>
                <label class="column-12 custom-indicators disabled" for="check-seven">
                    <input value="radio" name="radio-2" id="check-seven" type="radio" disabled>
                    <span class="custom-radiobutton"></span>
                    Radio disabled
                </label>
                <label class="column-12 custom-indicators disabled" for="check-eight">
                    <input value="radio" name="radio-2" id="check-eight" type="radio" checked disabled>
                    <span class="custom-radiobutton"></span>
                    Radio selected en disabled
                </label>
            </div>
            <pre class="row code-box" id="code-10"></pre>

        </div>
        <div class="column-12">
            <h2>Switches</h2>
            <div class="row input-group stack">
                <div class="column">
                    <div class="custom-indicators" id="clone-9">
                        <span>Primary</span>
                        <input class="switch switch-primary" id="switch-1" type="checkbox" checked/>
                        <label class=" custom-switch switch-btn" for="switch-1"></label>
                    </div>
                </div>

                <div class="column">
                    <div class="custom-indicators">
                        <span>secondary</span>
                        <input class="switch switch-secondary" id="switch-2" type="checkbox"/>
                        <label class=" custom-switch switch-btn" for="switch-2"></label>
                    </div>
                </div>
                <div class="column">
                    <div class="custom-indicators">
                        <span>Tertiary</span>
                        <input class="switch switch-tertiary" id="switch-3" type="checkbox"/>
                        <label class=" custom-switch switch-btn" for="switch-3"></label>
                    </div>
                </div>
            </div>
            <pre class="row code-box" id="code-9"></pre>

        </div>
    </section>

<?php include(__DIR__.'/../_partials/footer.php'); ?>