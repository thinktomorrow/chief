<?php include(__DIR__.'/../_partials/header.php'); ?>

<section class="stack-l forms" id="forms">
    <h1>Form elements</h1>
    <hr>

    <h2>Input fields</h2>
    <div class="row gutter stack">
        <div class="input-group column" id="clone-12">
            <label for="text">Text Input</label>
            <input id="text" class="input inset-s" placeholder="Text input" type="text" required>
            <small class="caption">Note about this field</small>
        </div>
        <div class="input-group column valid">
            <label for="text">Valid Input</label>
            <input id="text" class="input inset-s" placeholder="Text input" type="text" required>
            <small class="caption">Note about this field</small>
        </div>
        <div class="input-group column error">
            <label for="text">Error input</label>
            <input id="text" class="input inset-s" placeholder="Text input" type="text" required>
            <small class="caption">Note about this field</small>
        </div>
    </div>
    <pre class="row code-box" id="code-12"></pre>


    <h2>Other Form Elements</h2>
    <div class="row gutter">
        <div class="input-group column">
            <label for="password">Password</label>
            <input id="password" class="input inset-s" placeholder="*****" type="password" required>
        </div>
        <div class="input-group column">
            <label for="number">Number</label>
            <input id="number" class="input inset-s" placeholder="---- / -- . -- . --" type="phone" required>
        </div>
        <div class="input-group column">
            <label for="search">Search</label>
            <div class="search-wrapper">
                <input id="search" class="input inset-s" placeholder="Search" type="search" data-icon="icon-search" required>
            </div>
        </div>
    </div>
    <div class="row gutter">
        <div class="input-group column">
            <label for="email">Email Address</label>
            <input id="email" class="input inset-s" type="email" placeholder="you@example.com" required>
        </div>

        <div class="input-group column">
            <div class="select-wrapper">
                <label for="select">Select</label>
                <select id="select">
                    <option selected>Open this select menu</option>
                    <option>Option 1</option>
                    <option>Option 2</option>
                </select>
            </div>
        </div>

        <div class="input-group column">
            <label for="file">Upload</label>
            <label class="custom-file">
                <input type="file" id="file">
                <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
            </label>
        </div>
    </div>
    <div class="input-group stack">
        <label for="email">Textarea</label>
        <textarea rows="8" cols="80" id="message" class="input inset-s" required></textarea>
    </div>
</section>

<?php include(__DIR__.'/../_partials/footer.php'); ?>