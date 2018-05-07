<?php include(__DIR__.'/../_partials/header.php'); ?>

<section id="dropdown">

    <h2>Dropdown</h2>
    <p>In geval van een standaard dropdown button, is de volgende component geschikt.</p>
    <div class="panel stack-l">
        <div class="inset-l --border">
            <button-dropdown btn_name="toggle title" btn_class="btn btn-secondary">
                <p v-cloak>Hallo, ik zit in een dropdown.</p>
            </button-dropdown>
        </div>

        <pre><code class="html">    <?= htmlspecialchars('<dropdown>
        <a slot="trigger" slot-scope="{ toggle }" @click="toggle()">Toggle dropdown</a>
        <div v-cloak class="...">
            Hallo, ik zit in een dropdown.
        </div>
    </dropdown>'); ?>
</code></pre>
    </div>
    
    <h2>Dropdown</h2>
    <p>Voor volledige controle van de trigger link, kan je volgende opzet hanteren. Hierbij moet je rekening houden dat je de toggle click juist opzet. Hieronder een voorbeeld.</p>

    <div class="panel stack-l">
        <div class="inset-l --border">
            <dropdown>
                <a class="btn btn-link" slot="trigger" slot-scope="{ toggle }" @click="toggle">Toggle dropdown</a>
                <div v-cloak class="dropdown-target-default" slot-scope="{ toggle }">
                    <p>Hallo, ik zit in een dropdown.</p>
                    <button class="btn btn-primary squished-xs" @click="toggle">Ok ik snap het!</button>
                </div>
            </dropdown>
        </div>

    <pre><code class="html">    <?= htmlspecialchars('<dropdown>
        <a slot="trigger" slot-scope="{ toggle }" @click="toggle()">Toggle dropdown</a>
        <div v-cloak class="...">
            Hallo, ik zit in een dropdown.
        </div>
    </dropdown>'); ?>
</code></pre>
    </div>

    <div class="panel stack-l">
        <div class="inset-l --border">
            <dropdown>
                <button style="outline:none;" class="btn btn-primary" slot="trigger" slot-scope="{ toggle }" @click="toggle">Toggle dropdown</button>
                <div v-cloak class="dropdown-target-default" slot-scope="{ toggle }">
                    <p>Hallo, ik zit in een dropdown.</p>
                    <button class="btn btn-primary squished-xs" @click="toggle">Ok ik snap het!</button>
                </div>
            </dropdown>
        </div>

        <pre><code class="html">    <?= htmlspecialchars('<dropdown>
        <a slot="trigger" slot-scope="{ toggle }" @click="toggle()">Toggle dropdown</a>
        <div v-cloak class="...">
            Hallo, ik zit in een dropdown.
        </div>
    </dropdown>'); ?>
</code></pre>
    </div>

</section>

<?php include(__DIR__.'/../_partials/footer.php'); ?>
