<?php $view->script('docs-admin-index' , 'docs:app/bundle/admin/index.js' , ['vue']) ?>
<?php $view->style('docs-style' , 'docs:dist/css/docs.min.css') ?>
<section id="app">
    <div uk-grid>
        <div class="uk-width-medium@m">
            <div>
                <ul class="docs-category" uk-sortable>
                    <li v-for="(category , id) in orderByCategories" :id="id">
                        <span class="handler" uk-icon="menu"></span>
                        <span class="uk-margin-small-left">{{category.title}}</span>
                        <div class="uk-align-right">
                            <ul class="uk-grid uk-grid-small">
                                <li><span :class="{
                                    'pk-icon-circle-danger':category.status == 0,
                                    'pk-icon-circle':category.status == 1,
                                    'pk-icon-circle-warning':category.status == 2,
                                    'pk-icon-circle-success':category.status == 3,
                                }"></span></li>
                                <li><a @click.prevent="openModal(category)" uk-icon="icon:file-edit;ratio:0.9"></a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <button @click.prevent="openModal(draftCategory)" class="uk-margin-small uk-button uk-button-default uk-width-expand">{{'Add Category' | trans}}</button>
        </div>

        <div class="uk-width-expand">
            <div class="">
                <div class="uk-text-right">
                    <a :href="$url('admin/docs/post/edit')" class="uk-button uk-button-primary">{{'Add Post' | trans}}</a>
                </div>
            </div>
            <v-pagination :pages="pages" v-model="config.page" v-show="pages > 1 || config.page > 0"></v-pagination>
        </div>
    </div>

    
    <v-modal ref="modal" :options="{bgClose: false,escClose: false,}">
    <form @submit.prevent="saveCategory(modalDraft , true)">
        <div class="uk-modal-header uk-flex uk-flex-middle">
            <h2 v-if="modalDraft.id">{{'Edit Category' | trans}}</h2>
            <h2 v-else>{{'New Category' | trans}}</h2>
        </div>

        <div class="uk-modal-body">
            <div class="uk-margin">
                <label class="uk-form-label">{{'Title' | trans}}</label>
                <div class="uk-form-controls">
                    <input type="text" v-model="modalDraft.title" class="uk-width-expand uk-input" required>
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">{{'Slug' | trans}}</label>
                <div class="uk-form-controls">
                    <input type="text" v-model="modalDraft.slug" class="uk-width-expand uk-input">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">{{'Status' | trans}}</label>
                <div class="uk-form-controls">
                    <select class="uk-width-expand uk-select" v-model="modalDraft.status" required>
                        <option v-for="(status , id) in statuses" :value="id">{{status}}</option>
                    </select>
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">{{'Restrict Access' | trans}}</label>
                <div class="uk-form-controls uk-margin-top">
                    <p v-for="role in roles" class="uk-margin-small">
                        <label><input type="checkbox" :value="role.id" class="uk-checkbox" v-model="modalDraft.roles" number> {{ role.name }}</label>
                    </p>
                </div>
            </div>
        </div>

        <div class="uk-modal-footer uk-text-right">
            <a class="uk-button uk-button-text uk-margin-right" @click.prevent="close">{{ 'Close' | trans }}</a>
            <button v-if="modalDraft.id" class="uk-button uk-button-primary" type="submit">{{ 'Save' | trans }}</button>
            <button v-else class="uk-button uk-button-primary" type="submit">{{ 'Add' | trans }}</button>
        </div>
    </form>
    </v-modal>
   
</section>