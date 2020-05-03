<template>
    <div class="uk-grid" uk-grid>
        <div class="uk-width-expand">
            <div class="uk-margin">
                <label class="uk-form-label">{{'Title' | trans}}</label>
                <div class="uk-form-controls">
                     <v-input view="class: uk-input uk-width-expand" type="text" name="title" v-model="query.title" :rules="{required: true}" :message="'You have to enter a title' | trans" />
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{'Content' | trans}}</label>
                <div class="uk-form-controls">
                    <v-editor v-model="query.content" :options="{markdown : true , height: 300}" />
                </div>
            </div>

        </div>
        <div class="uk-width-medium">

            <div class="uk-margin">
                <label class="uk-form-label">{{'Slug' | trans}}</label>
                <div class="uk-form-controls">
                    <input type="text" class="uk-input uk-width-expand" v-model="query.slug">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{'Status' | trans}}</label>
                <div class="uk-form-controls">
                    <select class="uk-select uk-width-expand" v-model.number="query.status">
                        <option v-for="(status , id) in data.statuses" :key="id" :value="id">{{status}}</option>
                    </select>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{'Category' | trans}}</label>
                <div class="uk-form-controls">
                    <select class="uk-select uk-width-expand" v-model.number="query.category_id">
                        <option v-for="(category , id) in data.categories" :key="id" :value="category.id">{{category.title}}</option>
                    </select>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{'Date' | trans}}</label>
                <div class="uk-form-controls">
                    <input-date v-model="query.date"/>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props:['query' , 'data'],

        section:{
            label:'Settings',
            priority:0
        }
    }
</script>