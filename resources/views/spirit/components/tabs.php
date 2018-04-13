<?php include(__DIR__.'/../_partials/header.php'); ?>

<section id="tabs">
    <h2>Tabs</h2>
    <h3 id="tabs1">Basic tabs</h3>

    <div class="panel stack-l">
        <div class="inset-l">
            <tabs style="min-height:150px;" v-cloak>
                <tab name="first">
                    <p>
                        Sed fringilla placerat velit vel congue. Aliquam erat volutpat. Suspendisse tempus commodo ex vitae eleifend. Praesent finibus ullamcorper cursus. Morbi et nunc sit amet lacus luctus accumsan.
                    </p>
                </tab>
                <tab name="second">
                    <p>
                        Ut elementum lorem vel urna convallis dictum. Nullam porttitor posuere tellus eu congue.
                    </p>
                </tab>
                <tab name="third">
                    <p>
                        Suspendisse varius augue et felis pellentesque, feugiat hendrerit erat finibus. Aenean neque leo, egestas nec mi vitae, efficitur auctor nisl. Vivamus pulvinar dolor ex, eu consectetur justo pretium eget.
                    </p>
                </tab>
            </tabs>
        </div>

    <pre><code class="html">    <?= htmlspecialchars('<tabs>
        <tab name="first">
            // Content of first tabpanel...
        </tab>
        <tab name="second">
            // Content of second tabpanel...
        </tab>
        <tab name="third">
            // Content of third tabpanel...
        </tab>
    </tabs>'); ?>
        </code></pre>
    </div>

    <h4>Tab panels</h4>
    <p>First, define the <code><?= htmlspecialchars('<tabs></tabs>'); ?></code> element. This serves as the container of your tab navigation and panels.</p>
    <p>Each tab is set as an <code><?= htmlspecialchars('<tab></tab>'); ?></code> element. Add one for each tab you would like to create.</p>
    <p>The content of the tab panel is placed within the <code><?= htmlspecialchars('<tab></tab>'); ?></code> element. This can be regular html.</p>

    <h4>Tab navigation</h4>
    <p>Now you have content but still no navigation for each tab. The easiest way is to add the <code>name</code> attribute to the tab. e.g. <code><?= htmlspecialchars('<tab name="first tab">'); ?></code>. This will serve as the navigation link.</p>
    <p>By default the first tab will be active. If you'd like to display a different tab panel on pageload, you can force it by adding the <code>:active="true"</code> attribute to this tab.</p>

    <h3 id="tabs2">Custom tab navigation</h3>

    <div class="panel stack-l">
        <div class="inset-l">
            <tabs style="min-height:150px;">
                <template slot="tabnav" slot-scope="rows">
                    <div class="inline-group-s stack-s">
                        <a  v-for="tab in rows.tabs"
                            v-html="tab.name"
                            :href="tab.hash"
                            :aria-controls="tab.hash"
                            :aria-selected="tab.isActive"
                            role="tab"
                            class="inline-block squished-s btn"
                            :class="{'btn-tertiary': !tab.isActive, 'btn-primary' : tab.isActive }"
                        ></a>
                    </div>
                </template>
                <tab name="first" v-cloak>
                    <p>
                        Sed fringilla placerat velit vel congue. Aliquam erat volutpat. Suspendisse tempus commodo ex vitae eleifend. Praesent finibus ullamcorper cursus. Morbi et nunc sit amet lacus luctus accumsan.
                    </p>
                </tab>
                <tab name="second" v-cloak>
                    <p>
                        Ut elementum lorem vel urna convallis dictum. Nullam porttitor posuere tellus eu congue.
                    </p>
                </tab>
                <tab name="third" v-cloak>
                    <p>
                        Integer accumsan placerat urna eget euismod. Suspendisse varius augue et felis pellentesque, feugiat hendrerit erat finibus. Aenean neque leo, egestas nec mi vitae, efficitur auctor nisl. Vivamus pulvinar dolor ex, eu consectetur justo pretium eget.
                    </p>
                </tab>
            </tabs>
        </div>

        <pre><code class="html"> <?= htmlspecialchars('<tabs>
                <template slot="tabnav" slot-scope="rows">
                    <a  v-for="tab in rows.tabs"
                        v-html="tab.name"
                        :href="tab.hash"
                        :class="{\'btn btn-tertiary\': !tab.isActive, \'btn btn-primary\' : tab.isActive }"
                    ></a>
                </template>
                <tab name="first">
                    // Content of first tab...
                </tab>
                ...
            </tabs>'); ?>
        </code></pre>
    </div>

    <p>You can define a custom <code><?= htmlspecialchars('<template slot="tabnav"></template>'); ?></code> where you can place your custom navigation.
    </p>
    <p>This is a scoped slot so you will have access to the <code>tabs</code>
        values within this template via a slot-scope attribute, e.g. <code><?= htmlspecialchars('<template slot="tabnav" slot-scope="rows">'); ?></code>.
    </p>
    <p>Give each of the tabs an unique id: <code><?= htmlspecialchars('<tab id="tab-1">'); ?></code>. Next you point the href of your external links to this id, e.g. <code><?= htmlspecialchars('<a href="#tab-1">external link</a>'); ?></code></p>


    <h3 id="tabs3">Tabs with external navigation</h3>

    <div class="stack">
        <div class="panel">
            <div class="row gutter-s">

                <div class="column-4 bc-secondary inset-l">
                    <ul class="text-right">
                        <li class="block"><a class="block" href="#tabs3-1">first tab</a></li>
                        <li class="block"><a class="block" href="#tabs3-2">second tab</a></li>
                        <li class="block"><a class="block" href="#tabs3-3">third tab</a></li>
                    </ul>
                </div>
                <div class="column inset-l">
                    <tabs :external_nav="true">
                        <tab id="tabs3-1">
                            <p>
                                Sed fringilla placerat velit vel congue. Aliquam erat volutpat. Suspendisse tempus commodo ex vitae eleifend. Praesent finibus ullamcorper cursus. Morbi et nunc sit amet lacus luctus accumsan.
                            </p>
                        </tab>
                        <tab id="tabs3-2">
                            <p>
                                Ut elementum lorem vel urna convallis dictum. Nullam porttitor posuere tellus eu congue.
                            </p>
                        </tab>
                        <tab id="tabs3-3">
                            <p>
                                Integer accumsan placerat urna eget euismod. Suspendisse varius augue et felis pellentesque, feugiat hendrerit erat finibus. Aenean neque leo, egestas nec mi vitae, efficitur auctor nisl. Vivamus pulvinar dolor ex, eu consectetur justo pretium eget.
                            </p>
                        </tab>
                    </tabs>
                </div>

            </div>
        </div>
    </div>

    <p>If you ever need to control tab panels from outside the tab structure, you can define regular anchor tags. Try to use the anchor element so you can add the href attribute. This will ensure the url will contain the active tab reference.</p>
    <p>Use <code>v-cloak</code> attribute on the <code><?php htmlspecialchars('<tab>'); ?></code> elements to avoid the annoying flickr on pageload. An html element with v-cloak will be hidden until vue has been loaded.</p>

    <h3 id="tabs4">Specific case: form translation tabs</h3>

    <div class="panel">
        <div class="inset-l">
            <translation-tabs>
                <tab name="Nederlands" :options="{flag: 'flag-be'}">
                    <p>
                        Sed fringilla placerat velit vel congue. Aliquam erat volutpat. Suspendisse tempus commodo ex vitae eleifend. Praesent finibus ullamcorper cursus. Morbi et nunc sit amet lacus luctus accumsan.
                    </p>
                </tab>
                <tab name="Frans" :options="{flag: 'flag-fr', hasErrors: true}">
                    <p>
                        Ut elementum lorem vel urna convallis dictum. Nullam porttitor posuere tellus eu congue.
                    </p>
                </tab>
                <tab name="Engels" :options="{flag: 'flag-fr', hasErrors: false}">
                    <p>
                        Integer accumsan placerat urna eget euismod. Suspendisse varius augue et felis pellentesque, feugiat hendrerit erat finibus. Aenean neque leo, egestas nec mi vitae, efficitur auctor nisl. Vivamus pulvinar dolor ex, eu consectetur justo pretium eget.
                    </p>
                </tab>
            </translation-tabs>
        </div>
        <pre><code class="html"><?= htmlspecialchars('<translation-tabs>
        <tab name="Nederlands" :options="{flag: \'flag-be\'}">
            content of translation first tab ....
        </tab>
        <tab name="Frans" :options="{flag: \'flag-fr\', hasErrors: true}">
            content of translation second tab ....
        </tab>
        <tab name="Engels" :options="{flag: \'flag-fr\', hasErrors: false}">
            content of translation third tab ....
        </tab>
    </translation-tabs>'); ?></code></pre>
    </div>

</section>

<?php include(__DIR__.'/../_partials/footer.php'); ?>
