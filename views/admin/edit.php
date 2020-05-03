<?php $view->script('docs_admin_edit' , 'docs:app/bundle/admin/edit.js' , ['vue' , 'editor']) ?>
<?php $view->style('docs_style' , 'docs:dist/css/docs.min.css') ?>

<form id="app" @submit.prevent="save" v-cloak>
    <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
        <div>
            <h2 class="uk-margin-remove" v-if="query.id">{{ 'Edit Docs' | trans }}</h2>
            <h2 class="uk-margin-remove" v-else>{{ 'Add Docs' | trans }}</h2>
        </div>
        <div class="uk-margin">
            <a class="uk-button uk-button-text uk-margin-right" :href="$url.route('admin/docs/post')">{{ query.id ? 'Close' : 'Cancel' | trans }}</a>
            <button class="uk-button uk-button-primary" :disabled="!query.title" type="submit">
                <span class="uk-text-middle">{{ 'Save' | trans }}</span>
            </button>
        </div>
    </div>

    <ul ref="tab" v-show="sections.length > 1" id="area-tab">
        <li v-for="section in sections" :key="section.name"><a>{{ section.label | trans }}</a></li>
    </ul>

    <div class="uk-switcher uk-margin" ref="content" id="area-content">
        <div v-for="section in sections" :key="section.name">
            <component :is="section.name" :query.sync="query" :data.sync="data" ></component>
        </div>
    </div>
</form>