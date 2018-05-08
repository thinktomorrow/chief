<?php include(__DIR__.'/../_partials/header.php'); ?>

<section id="dropdown">

    <h1>Dropdown</h1>

    <h2>Standaard dropdown</h2>
    <p>In geval van een standaard dropdown button, is de <code><?= htmlspecialchars('<button-dropdown>') ?></code> component geschikt. De dropdown sluit zich door: op de knop opnieuw te klikken, buiten de dropdown te klikken of op <code>esc</code> toets.</p>
    <div class="panel stack-l">
        <div class="inset-l --border">
            <button-dropdown btn_name="klik op mij">
                <p v-cloak>Hallo, ik zit in een dropdown.</p>
            </button-dropdown>
        </div>

        <pre><code class="html">    <?= htmlspecialchars('<button-dropdown btn_name="klik op mij">
        <p v-cloak>Hallo, ik zit in een dropdown.</p>
    </button-dropdown>'); ?>
</code></pre>
    </div>

    <h2>Titel en uiterlijk</h2>
    <p>De classe van de button is standaard <code>btn btn-primary</code> maar die kan je aanpassen door een <code>btn_class</code> attribuut op de component toe toe voegen.</p>
    <p>De titel van de button kan je aanpassen via de <code>btn_name</code> attribuut. Hierin wordt ook html aanvaard. De inhoud van de dropdown zelf aanvaardt ook html.</p>
    <div class="panel stack-l">
        <div class="inset-l --border">
            <button-dropdown class="stack" btn_name="klik op mij" btn_class="btn btn-tertiary">
                <p v-cloak>Hallo, ik zit in een dropdown in een ander jasje.</p>
            </button-dropdown>
            <button-dropdown btn_name="<span class='inline-s'>Hey jij daar, klikken!</span><span class='icon icon-arrow-down'></span>">
                <div v-cloak>
                    <p>Hallo, ik zit in een dropdown met aangepast uiterlijk. <br>Wat vind je ervan?</p>
                    <div class="row">
                        <button class="column-6 bg-success center-center squished-s">
                            <span class="icon icon-check"></span>
                        </button>
                        <button class="column-6 bg-error center-center squished-s">
                            <span class="icon icon-x"></span>
                        </button>
                    </div>
                </div>
            </button-dropdown>
        </div>
        <pre><code class="html">    <?= htmlspecialchars('<button-dropdown btn_name="toggle title" btn_class="btn btn-tertiary">
        <p v-cloak>Hallo, ik zit in een dropdown.</p>
    </button-dropdown>'); ?>
</code></pre>
        <pre><code class="html">    <?= htmlspecialchars('<button-dropdown btn_name="toggle title" btn_class="btn btn-tertiary">
        <div v-cloak>
            <p>Hallo, ik zit in een dropdown met aangepast uiterlijk. <br>Wat vind je ervan?</p>
            <div class="row">
                <button class="column-6 bg-success center-center squished-s">
                    <span class="icon icon-check"></span>
                </button>
                ...
            </div>
        </div>
    </button-dropdown>'); ?>
</code></pre>
    </div>

    <h2>Custom dropdown</h2>
    <p>Achterliggend maakt de <code>button-dropdown</code> component gebruik van de <code>dropdown</code> component. Voor volledige controle, kan je kiezen om deze component rechtstreeks te gebruiken. Je hebt hierbij mogelijkheid om de trigger en dropdown inhoud volledig te stijlen en hebt toegang tot de event handlers. </p>
    <p>Onze button dropdown kan bijvoorbeeld worden herschreven als volgt:</p>

    <div class="panel stack-l">
        <div class="inset-l --border">
            <dropdown>
                <button class="btn btn-primary" slot="trigger" slot-scope="{ toggle }" @click="toggle">Klik op mij, aub!</button>
                <div class="dropdown-box">
                    <p>Ik zit in de dropdown</p>
                </div>
            </dropdown>
        </div>

        <pre><code class="html">    <?= htmlspecialchars('<dropdown>
        <button class="btn btn-primary" slot="trigger" slot-scope="{ toggle }" @click="toggle">Klik op mij, aub!</button>
        <div class="dropdown-box">
            <p>Ik zit in de dropdown</p>
        </div>
    </dropdown>>'); ?>
</code></pre>
    </div>

    <p>Een voorbeeldje van een advanced aangepaste trigger en dropdown inhoud:</p>

    <div class="panel stack-l">
        <div class="inset-l --border">
            <dropdown>
                <button class="btn" :class="{'btn-tertiary' : isActive }" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Toggle dropdown</button>
                <div v-cloak class="panel panel-tertiary inset" style="background-color:#da1b61; margin-top:.5em;" slot-scope="{ toggle }">
                    <button class="btn squished-xs" @click="toggle">Ok ik snap het al!</button>
                </div>
            </dropdown>
        </div>

        <pre><code class="html">    <?= htmlspecialchars('<dropdown>
        <button class="btn" :class="{\'btn-tertiary\' : isActive }" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Toggle dropdown</button>
        <div v-cloak class="panel panel-tertiary inset" slot-scope="{ toggle }">
            <button @click="toggle">Ok ik snap het al!</button>
        </div>
    </dropdown>'); ?>
</code></pre>
    </div>

</section>

<?php include(__DIR__.'/../_partials/footer.php'); ?>
