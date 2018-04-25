<?php include(__DIR__.'/../_partials/header.php'); ?>

<section id="multiselect">
    <h2>Dropdown</h2>

    <div class="row gutter">
        <div class="column-2">

        </div>
        <div class="column-8">
            <section>
                <ul>
                    <li><a href="">dfdfd</a></li>
                    <li><a href="">dfdfd</a></li>
                    <li>
                        <dropdown>
                            <div slot-scope="{}">
                                HALLO
                            </div>
                            <a slot="trigger" slot-scope="{}">Publiceer</a>
                        </dropdown>
                    </li>
                    <li><a href="">dsfdsqfdfdfqsdf</a></li>
                    <li>
                        <dropdown>
                            <div v-cloak slot-scope="{}">
                                <div class="row">
                                    <div class="column-6">dfqsdfqsdf</div>
                                    <div class="column-6">fqsdfqsdfqsdf qsdfsqd
                                    fqsdfqsdf qsdfqsdf
                                    qsdfqsdfdf</div>

                                    <button class="btn btn-primary stack">DO IT</button>
                                </div>
                            </div>
                            <a slot="trigger" slot-scope="{}">Publiceer</a>
                        </dropdown>
                    </li>
                </ul>
            </section>
        </div>
    </div>

</section>

<?php include(__DIR__.'/../_partials/footer.php'); ?>
