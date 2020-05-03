var postIndex = {
    el:'#app',
    name: 'DocsIndex',
    data(){
        return _.merge({
            modalDraft:false,
            categories:false,
            posts:false,
            config:{
                filter: this.$session.get('documents.filter' , {order:'priority asc' , limit:50})
            },
            pages:0,
            count:'',
            selected:[],
            categorySortable: false
        } , window.$data)
    },

    mixins:[
        require('../../lib/index')
    ],

    created(){
        this.setCategorySortable();
        this.resource = this.$resource('admin/docs/api{/id}');
    },

    mounted(){
        const self = this;
        UIkit.util.on('.docs-category', 'moved', function (item) {
            self.setCategorySortable();
            self.priorityCheck(self.categorySortable);
        });
        this.$watch('config.page', this.load, { immediate: true });
    },

    watch: {
        'config.filter': {
            handler(filter) {
                if (this.config.page) {
                    this.config.page = 0;
                } else {
                    this.load();
                }
                this.$session.set('documents.filter', filter);
            },
            deep: true,
        }
    },

    computed:{
        categoryOptions() {
            const options = _.map(this.categories, (category, id) => ({ text: category.title, value: category.id }));
            return [{ label: this.$trans('Filter by'), options }];
        },

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
            this.resource.query({filter:this.config.filter , page:this.config.page}).then((res)=>{
                const { data } = res;
                this.$set(this, 'posts', data.posts);
                this.$set(this, 'pages', data.pages);
                this.$set(this, 'count', data.count);
                this.$set(this, 'selected', []);
            }).catch((err)=>{
                this.$notify(err.bodyText , 'danger') 
            })
        },

        setCategorySortable(){
            this.categorySortable = document.getElementsByClassName('docs-category')[0].children;
        },

        deleteCategory(id){
            this.$http.get('admin/docs/api/bulkcategorydelete' , {
                params:{id:id}
            }).then((res)=>{
                location.reload();
            }).catch((err)=>{
                this.$notify(err.data , 'danger');
            })
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

        status(status) {
            const posts = this.getSelected();

            posts.forEach((post) => {
                post.status = status;
            });

            this.resource.save({ id: 'bulk' }, { posts }).then(function () {
                this.load();
                this.$notify('Posts saved.');
            });
        },

        remove() {
            this.resource.delete({ id: 'bulk' }, { ids: this.selected }).then(function () {
                this.load();
                this.$notify('Posts deleted.');
            });
        },

        getSelected() {
            return this.posts.filter(function (post) { return this.selected.indexOf(post.id) !== -1; }, this);
        }
    }
}
export default postIndex
Vue.ready(postIndex)
