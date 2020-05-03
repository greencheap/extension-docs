<template>
    <div>
        <v-meta-tag :source.sync="query"></v-meta-tag>
    </div>
</template>

<script>
    export default {
        props:['query' , 'data'],

        section:{
            label:'Meta',
            priority:100
        }
    }
</script>