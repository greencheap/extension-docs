module.exports = {
    el:'#app',
    name: 'DocsIndex',
    data(){
        return _.merge({
            modalDraft:false,
            categories:false,
            posts:false,
            config:{
                filter: this.$session.get('docs.filter' , {order:'priority asc' , limit:50})
            },
            pages:0,
            count:'',
            selected:[],
            categorySortable: false
        } , window.$data)
    },

    created(){
        this.setCategorySortable();
    },

    mounted(){
        const self = this;
        UIkit.util.on('.docs-category', 'moved', function (item) {
            self.setCategorySortable();
            self.priorityCheck(self.categorySortable);
        });
    },

    watch: {
        'docs.filter': {
            handler(filter) {
                if (this.config.page) {
                    this.config.page = 0;
                } else {
                    this.load();
                }
                this.$session.set('docs.filter', filter);
            },
            deep: true,
        }
    },

    computed:{
        draftCategory(){
            return {
                id:null,
                title:null,
                slug:null,
                status:3,
                roles:[]
            }
        },

        orderByCategories: function () {
            return this.categories;
        }
    },

    methods:{
        load(){
            console.log('Hello World')
        },

        setCategorySortable(){
            this.categorySortable = document.getElementsByClassName('docs-category')[0].children;
        },

        saveCategory(item , reload = false){
            this.$http.post('admin/docs/api/savecategory' , {category:item , id:item.id}).then((res)=>{
                if(!item.id){
                    location.reload();
                }
                if(reload){
                    location.reload();
                }
            }).catch((err)=>{
                this.$notify(err.bodyText , 'danger') 
            })
        },

        priorityCheck(object){
            for (const key in object) {
                if (object.hasOwnProperty(key)) {
                    const id = object[key].id;
                    this.categories[id].priority = parseInt(key);
                    this.saveCategory(this.categories[id]);
                }
            }
        },

        openModal(data){
            this.modalDraft = data;
            this.$refs.modal.open();
            UIkit.util.on(this.$refs.modal.modal.$el, 'hide', this.onClose);
        },

        close() {
            this.modalDraft = this.draftCategory;
            this.scrollToEnd();
            this.$refs.modal.close();
        },

        scrollToEnd() {
            let container = this.$el.querySelector(".pk-pre");
            if (container && container.scrollHeight) container.scrollTop = container.scrollHeight;
        },

        onClose() {
            this.modalDraft = this.draftCategory
        },
    }
}

Vue.ready(module.exports)
