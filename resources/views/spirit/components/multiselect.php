<?php include(__DIR__.'/../_partials/header.php'); ?>

<section id="multiselect">
    <h2>Multiselect</h2>
    <h3 id="tabs1">Basic multiselect</h3>

    <div class="row gutter">
        <div class="column-2">
            <div class="stack-l">
                <h4 class="text-right"><a class="squished-s text-black" href="#setup">Setup</a></h4>
                <a class="block squished-s text-right" href="#setup">Setup</a>
                <a class="block squished-s text-right" href="#element">Element</a>
            </div>
            <div class="stack-l">
                <h4 class="text-right squished-s">Usecases</h4>
                <a class="block squished-s text-right" href="#single">Single select</a>
                <a class="block squished-s text-right" href="#multiple">Multiple select</a>
            </div>

            <section class="stack-l">
                <h4 class="text-right"><a class="squished-s text-black" href="#api">Api</a></h4>
                <a class="block squished-s text-right" href="#api-name">name</a>
                <a class="block squished-s text-right" href="#api-options">options</a>
                <a class="block squished-s text-right" href="#api-valuekey">valuekey</a>
                <a class="block squished-s text-right" href="#api-labelkey">labelkey</a>
                <a class="block squished-s text-right" href="#api-multiple">multiple</a>
                <a class="block squished-s text-right" href="#api-selected">selected</a>
                <a class="block squished-s text-right" href="#api-grouplabel">grouplabel</a>
                <a class="block squished-s text-right" href="#api-groupvalues">groupvalues</a>
            </section>
        </div>
        <div class="column-8">
            <section>
                <h3 id="setup">Setup</h3>

                <p>The multiselect component is a vue component which provides you with a better selection experience. It allows for single and multiple selections, supports autocomplete, grouped options and object rendering. </p>
                <p>The best thing is that as far as the form is concerned, it behaves exactly the same as a regular <?= htmlspecialchars( '<select>' ); ?> element</p>

                <h4 id="element">Element</h4>
                <p>First, define the element on the location you wish to inject the select input.
                <pre><code><?= htmlspecialchars('<chief-multiselect></chief-multiselect>'); ?></code></pre>
                </p>
                <p>The only required property on the element is <code><?= htmlspecialchars(':options'); ?></code>. This should contain an array of the available options.</p>
            </section>
            <h3 id="single">Single select</h3>
            <p>In order to use this as a form input, you need to add a <code><?= htmlspecialchars('name'); ?></code> property. Just as you would for a regular select.</p>
            <p>For a single select, the submitted form value will be a string value.</p>

            <div class="panel stack-l" style="overflow:visible;">
                <div class="inset-l">
                    <chief-multiselect
                            name="basicExample"
                            :options="['first','second','third']"
                    >
                    </chief-multiselect>
                </div>
                <pre><code class="html">
        <?= htmlspecialchars('<chief-multiselect
                        name="category"
                        :options="[\'first\',\'second\',\'third\']"
                >
        </chief-multiselect>'); ?>
            </code></pre>
            </div>

            <h3 id="multiple">Multiple select</h3>
            <p>By default a multiselect is setup for a single selection. Add a <code><?= htmlspecialchars(':multiple="true"'); ?></code> attribute to the element to allow for multiple selections.</p>
            <p>For a multiple select, the submitted form value will be an array.</p>

            <div class="panel stack-l" style="overflow:visible;">
                <div class="inset-l">
                    <chief-multiselect
                            name="basicExample"
                            :options="['first','second','third']"
                            :multiple="true"
                    >
                    </chief-multiselect>
                </div>
                <pre><code class="html">
        <?= htmlspecialchars('<chief-multiselect
                        name="category"
                        :options="[\'first\',\'second\',\'third\']"
                        :multiple="true"
                >
        </chief-multiselect>'); ?>
            </code></pre>
            </div>

            <section class="stack-l">
                <h3 id="api">Api reference</h3>
                <h4 id="api-name">name <span class="label">string</span></h4>
                <p>
                    Property that sets the form field name of the element.
                </p>
                <p>If you want to accept multiple selections, do not add square brackets, e.g. tagid[], yourself. For multiple selections,
                    the name value will automatically be set as an array and the square brackets are added via the component.</p>
                <p>
                    Example: <code><?= htmlspecialchars( '<chief-multiselect name="tagid"></chief-multiselect>' ); ?></code>
                </p>
            </section>

            <section class="stack-l">
                <h4 id="api-options">options <span class="label">array</span></h4>
                <p>
                    Property that sets the available list of options. The options array can be either:
                <ul>
                    <li>a list of primitive values. e.g. <code><?= htmlspecialchars( '[\'first\',\'second\',\'third\']' ); ?></code></li>
                    <li>a list of object values. e.g. <code><?= htmlspecialchars( '[{\'id\': 1, \'label\': \'first\'}, {id: 2, label: \'second\'}]' ); ?></code>. If you pass objects, you should also set the <code>valuekey</code> and <code>labelkey</code> properties.</li>
                </ul>
                </p>
                <p>
                    Example: <code><?= htmlspecialchars( '<chief-multiselect :options="[\'first\',\'second\',\'third\']"></chief-multiselect>' ); ?></code>
                </p>
            </section>
            <section class="stack-l">
                <h4 id="api-valuekey">valuekey <span class="label">string</span></h4>
                <p>
                    If you pass object values as the available options set, you need to set the valuekey to identify which object value should be used as the option value.
                <p>
                    Example:
                <pre><code><?= htmlspecialchars( '<chief-multiselect
                        name="basicExample"
                        :options="[{\'id\': 1, \'label\': \'first\'}, {id: 2, label: \'second\'}, {id: 3, label: \'third\'}]"
                        :multiple="true"
                        valuekey="id"
                        labelkey="label"
                >
    </chief-multiselect>' ); ?></code></pre>
                </p>
            </section>
            <section class="stack-l">
                <h4 id="api-labelkey">labelkey <span class="label">string</span></h4>
                <p>
                    Mostly used together with the valuekey, if you pass object values as the available options set, the labelkey identifies which object value should be used as the displayed option label.
                <p>
                    Example:
                <pre><code><?= htmlspecialchars( '<chief-multiselect
                        name="basicExample"
                        :options="[{\'id\': 1, \'label\': \'first\'}, {id: 2, label: \'second\'}, {id: 3, label: \'third\'}]"
                        :multiple="true"
                        valuekey="id"
                        labelkey="label"
                >
    </chief-multiselect>' ); ?></code></pre>
                </p>
            </section>
            <section class="stack-l">
                <h4 id="api-multiple">multiple <span class="label">boolean</span></h4>
                <p>
                    Allow for multiple selections if set to true. Default this is set to false which means only one value can be selected.
                    For multiple selections, the selected options are presented as pills instead of plain text. Pills can be removed by either the backspace or by clicking the x on the right side of each pill.
                </p>
                <p>
                    Example: <code><?= htmlspecialchars( ':multiple="true"' ); ?></code>
                </p>
            </section>

            <section class="stack-l">
                <h4 id="api-selected">selected <span class="label">string|object|array</span></h4>
                <p>
                    Sets the default selected values. You can pass the selected property the following data structures:
                <ul>
                    <li><strong>Single/Multiple string values. <code>string|array of strings</code></strong> For simple options lists, the selected value will be compared against each value. In case of object options, this will match the valuekey identifier.</li>
                    <li><strong>Single/Multiple objects. <code>object|array of objects</code></strong> You can also pass entire objects. These will be matched in their entirety so be sure you pass the object in the same format as the available objects in the option list.</li>
                </ul>

                <div class="panel stack-l" style="overflow:visible;">
                    <div class="inset-l">
                        <chief-multiselect
                                name="basicExample"
                                :options="['first','second','third']"
                                selected="second"
                        >
                        </chief-multiselect>
                        <chief-multiselect
                                class="stack"
                                :options="[{'id': 1, 'label': 'first'}, {id: 2, label: 'second'}, {id: 3, label: 'third'}]"
                                name="basicExample"
                                valuekey="id"
                                labelkey="label"
                                :multiple="true"
                                :selected="[2,3]"
                        >
                        </chief-multiselect>
                    </div>
                    <pre><code class="html">
    <?= htmlspecialchars('<chief-multiselect
                            name="category"
                            :options="[\'first\',\'second\',\'third\']"
                            selected="second"
                    >
    </chief-multiselect>'); ?></code><code class="html">
    <?= htmlspecialchars('<chief-multiselect
                            :options="[{\'id\': 1, \'label\': \'first\'}, {id: 2, label: \'second\'}, {id: 3, label: \'third\'}]"
                            name="basicExample"
                            valuekey="id"
                            labelkey="label"
                            :multiple="true"
                            selected="[2,3]"
                    >
    </chief-multiselect>'); ?>
            </code></pre>
                </div>
            </section>

            <section class="stack-l">
                <h4 id="api-grouplabel">grouplabel <span class="label">string</span></h4>
                <p>
                    When you have constructed your options array for grouped values, you'll need to set the <code>grouplabel</code> and <code>groupvalues</code> properties.
                    Grouplabel is the key on each entry that points to the title of each option group.
                </p>
            </section>

            <section class="stack-l">
                <h4 id="api-groupvalues">groupvalues <span class="label">string</span></h4>
                <p>
                    Groupvalues is the key that points to each options values array.
                    Note that you need to setup the options array specifically to allow for grouped values.
                    Also consider that only the values are being autocompletes, not the group labels since they are not selectable.
                </p>
                <div class="panel stack-l" style="overflow:visible;">
                    <div class="inset-l">
                        <chief-multiselect
                                class="stack"
                                :options="[{'label': 'first-group', 'values': ['firstie','second','third']}, {'label': 'second-group', 'values': ['fourth','fifth','sixth']}]"
                                name="basicExample"
                                :multiple="true"
                                grouplabel="label"
                                groupvalues="values"
                                selected='["second"]'
                        >
                        </chief-multiselect>
                    </div>
                    <pre><code class="html">
    <?= htmlspecialchars('<chief-multiselect
                            name="basicExample"
                            :options="[{\'label\': \'first-group\', \'values\': [\'first\',\'second\',\'third\']}, {\'label\': \'second-group\', \'values\': [\'fourth\',\'fifth\',\'sixth\']}]"
                            :multiple="true"
                            grouplabel="label"
                            groupvalues="values"
                    >
    </chief-multiselect>'); ?>
            </code></pre>
                </div>
            </section>
        </div><!-- end inner multiselect column -->
    </div>

</section>

<?php include(__DIR__.'/../_partials/footer.php'); ?>
