<?php $view->script('docs_admin_index' , 'docs:app/bundle/admin/index.js' , ['vue']) ?>
<?php $view->style('docs_style' , 'docs:dist/css/docs.min.css') ?>
<section id="app">
    <div uk-grid>
        <div class="uk-width-medium@m">
            <div v-if="categories.length">
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
            <div class="uk-card uk-card-body uk-card-default uk-text-center" v-if="!categories.length">{{'Not Found Categories' | trans}}</div>
            <button @click.prevent="openModal(draftCategory)" class="uk-margin-small uk-button uk-button-default uk-width-expand">{{'Add Category' | trans}}</button>
        </div>

        <div class="uk-width-expand">
            <div class="uk-flex uk-flex-middle uk-flex-between">
                <div class="uk-flex uk-flex-middle uk-flex-wrap" >
                    <h2 class="uk-h3 uk-margin-remove" v-if="!selected.length">{{ '{0} %count% Post|{1} %count% Post|]1,Inf[ %count% Posts' | transChoice(count, {count:count}) }}</h2>
                    <div class="uk-flex uk-flex-middle" v-else>
                        <h2 class="uk-h2 uk-margin-remove">{{ '{1} %count% Post selected|]1,Inf[ %count% Posts selected' | transChoice(selected.length, {count:selected.length}) }}</h2>
                        <div class="uk-margin-left" >
                            <ul class="uk-subnav pk-subnav-icon">
                                <li><a class="pk-icon-check pk-icon-hover" :uk-tooltip="'Publish' | trans" @click.prevent="status(3)"></a></li>
                                <li><a class="pk-icon-block pk-icon-hover" :uk-tooltip="'Unpublish' | trans" @click.prevent="status(2)"></a></li>
                                <li><a class="pk-icon-delete pk-icon-hover" :uk-tooltip="'Delete' | trans" @click.prevent="remove()" v-confirm="'Delete Post?'"></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="uk-search uk-search-default pk-search">
                        <span uk-search-icon></span>
                        <input class="uk-search-input" type="search" v-model="config.filter.search" debounce="300">
                    </div>
                </div>

                <div class="uk-text-right">
                    <a :href="$url('admin/docs/post/edit')" class="uk-button uk-button-primary" v-if="categories.length">{{'Add Post' | trans}}</a>
                    <button class="uk-button uk-button-primary" :title="'You need to add a category first.' | trans" uk-tooltip disabled v-else>{{'Add Post' | trans}}</button>
                </div>
            </div>
            <div class="uk-margin">
                <ul class="uk-list list-heading">
                    <li class="uk-grid-small" uk-grid>
                        <div class="uk-width-auto"><input class="uk-checkbox" type="checkbox" v-check-all:selected="{ selector: 'input[name=id]' }" number></div>
                        <div class="uk-width-expand">{{'Title' | trans}}</div>
                        <div class="uk-width-small uk-text-center">{{'Status' | trans}}</div>
                        <div class="uk-width-small uk-text-center">
                            <input-filter :title="$trans('Category')" :value.sync="config.filter.category_id" :options="categoryOptions" v-model="config.filter.category_id"></input-filter>
                        </div>
                        <div class="uk-width-small uk-text-center">{{'Url' | trans}}</div>
                    </li>
                </ul>
                <hr>
                <ul class="uk-list uk-list-divider uk-list-large list-post" uk-sortable>
                    <li v-for="(post , id) in posts" :key="id" :id="id" class="uk-grid-small" uk-grid>
                        <div class="uk-width-auto"><input class="uk-checkbox" type="checkbox" name="id" :value="post.id"></div>
                        <div class="uk-width-expand"><span class="handler uk-margin-small-right" uk-icon="menu"></span><a :href="$url.route('admin/docs/post/edit', { id: post.id })">{{ post.title }}</a></div>
                        <div class="uk-width-small uk-text-center">
                            <span :class="{
                                'pk-icon-circle-danger':post.status == 0,
                                'pk-icon-circle':post.status == 1,
                                'pk-icon-circle-warning':post.status == 2,
                                'pk-icon-circle-success':post.status == 3 && post.published,
                                'pk-icon-schedule': post.status == 3 && !post.published
                            }"></span>
                        </div>
                        <div class="uk-width-small uk-text-center">{{post.category_name | trans}}</div>
                        <div class="uk-width-small uk-text-center">{{post.url | trans}}</div>
                    </li>
                </ul>
            </div>
            <h3 class="uk-h2 uk-text-muted uk-text-center" v-show="posts && !posts.length">{{ 'No posts found.' | trans }}</h3>
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
                <button v-if="modalDraft.id" @click.prevent="deleteCategory(modalDraft.id)" type="button" :disabled="modalDraft.hasPost" class="uk-button uk-button-danger">{{ 'Delete' | trans }}</button>
                <button v-if="modalDraft.id" class="uk-button uk-button-primary" type="submit">{{ 'Save' | trans }}</button>
                <button v-if="!modalDraft.id" class="uk-button uk-button-primary" type="submit">{{ 'Add' | trans }}</button>
            </div>
        </form>
    </v-modal>
   
</section>