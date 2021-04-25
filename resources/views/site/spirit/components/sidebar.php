<?php include(__DIR__ . '/../_partials/header.php'); ?>

<section class="stack-l components" id="sidebars">
    <h1>Sidebar</h1>
    <hr>

    <modal type="sidebar" id="exampleModal2" title="Welcome to this sidebar">
        <div v-cloak>
            <div class="stack-s">
                <a class="btn">First link</a><br>
                <hr>
                <a class="btn">Secon link</a><br>
                <hr>
                <a class="btn">Third link</a>
            </div>

            <div class="stack-m">
                <a class="btn btn-primary-outline" @click="closeModal('exampleModal2')">Oke, Got it</a>
            </div>
        </div>
    </modal>

    <div class="stack-m">
        <button class="btn btn-primary-outline" @click="showModal('exampleModal2')">Show sidebar</button>
    </div>

    <p>The modal component can also be used as an offcanvas sidebar. This is ideal for presenting secondary information, context screens or small form fields. Just add an attribute <em>type="sidebar"</em> on the modal element.</p>
    <pre>
        <code class="html"><?= htmlentities('<modal type="sidebar" id="exampleModal">
    <div v-cloak>
        <div class="stack-s">
            <a class="btn">First link</a><br>
            <hr>
            <a class="btn">Secon link</a><br>
            <hr>
            <a class="btn">Third link</a>
        </div>

        <div class="stack-m">
            <a class="btn btn-primary-outline" @click="closeModal(\'exampleModal2\')">Oke, Got it</a>
        </div>
    </div>
</modal>'); ?>
        </code>
    </pre>

</section>

<?php include(__DIR__ . '/../_partials/footer.php'); ?>
