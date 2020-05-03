module.exports = {

    data(){
        return {
            postsSortable: false
        }
    },

    created(){
        this.setPostsSortable();
    },

    mounted(){
        const self = this;
        UIkit.util.on('.list-post', 'moved', function (item) {
            self.setPostsSortable();
            self.priorityCheckPosts(self.postsSortable);
        });
    },

    methods:{
        setPostsSortable(){
            this.postsSortable = document.getElementsByClassName('list-post')[0].children;
        },

        priorityCheckPosts(object){
            for (const key in object) {
                if (object.hasOwnProperty(key)) {
                    const id = object[key].id;
                    this.posts[id].priority = parseInt(key);
                    this.savePosts(this.posts[id]);
                }
            }
        },

        savePosts(item , reload = false){
            this.$http.post('admin/docs/api/save' , {data:item , id:item.id}).then((res)=>{
                
            }).catch((err)=>{
                this.$notify(err.bodyText , 'danger') 
            })
        }
    }
}