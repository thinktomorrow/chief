<template>

    <tabs>

        <template slot="tabnav" slot-scope="rows">
            <nav class="translation-tabs-nav inline-group-s stack-s">
                <a v-for="tab in rows.tabs"
                   :href="tab.hash"
                   :aria-controls="tab.hash"
                   :aria-selected="tab.isActive"
                   role="tab"
                   class="inline-block squished-s"
                   :class="{'active': tab.isActive, 'tab-error': tab.options.hasErrors }"
                >
                    <span class="flag" :class="tab.options.flag"></span>
                    <span class="tabs-name" v-if="tab.isActive">{{ tab.name }}</span>
                </a>
            </nav>
        </template>
        <slot></slot>
    </tabs>

</template>