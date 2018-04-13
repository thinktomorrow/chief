<?php include(__DIR__.'/../_partials/header.php'); ?>

<section class="stack-l components" id="modals">
    <h1>Modal</h1>
    <hr>

    <modal id="exampleModal1" title="Welcome to this infosplash">
        <div v-cloak>
            <p>
                Aliquid animi, commodi cupiditate doloremque doloribus dolorum ea facere facilis illo,.
                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
            </p>

            <div class="stack-m">
                <a class="btn-link" @click="closeModal('exampleModal1')">Oke, Got it</a>
            </div>
        </div>
    </modal>

    <div class="stack-m">
        <button class="btn btn-secondary" @click="showModal('exampleModal1')">Open modal</button>
    </div>

    <p>The modal component is a custom vue component which can be instantiated with the <em><?= htmlentities('<modal>'); ?></em> tag.    </p>
    <pre>
        <code class="html"><?= htmlentities('<modal id="exampleModal"></modal>'); ?>
        </code>
    </pre>
    <p>An id attribute is required in order to target the modal. You can have multiple modals on one page so make sure you assign a unique id for each one.</p>

    <p>The main content of the modal is placed inside the tag and accepts html markup. You can pass an optional <em>title</em> attribute which will be used as the title of the modal:</p>
    <pre><code class="html"><?= htmlentities('<modal id="exampleModal"'); ?><strong>title="This is the title"</strong><?= htmlentities('>
    This is the content of the modal
</modal>'); ?>
</code>
    </pre>

    <p>You can trigger the modal by triggering the 'showModal' click event. This function is passed the id of the modal element. Put the trigger on a button like so:</p>
    <pre>
        <code class="html">
<?= htmlentities('<button @click="showModal(\'exampleModal\')">Open modal</button>' ); ?>
        </code>
    </pre>

    <p>If you would like to activate the modal upon pageload and let it display immediately, you can add the <em>:active</em> attribute.</p>
    <pre>
        <code class="html">
<?= htmlentities('<modal :active="true" id="exampleModal"> ... </modal>' ); ?>
        </code>
    </pre>

</section>

<?php include(__DIR__.'/../_partials/footer.php'); ?>
