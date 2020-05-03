import Settings from '../../components/post-edit-settings.vue';
import Meta from '../../components/post-edit-meta.vue';
window.Docs = {
    el: '#app',
    name: 'DocsEdit',
    data(){
        return _.merge({
            sections: [],
            active: this.$session.get('docs.edit.tab.active', 0),
        } , window.$data)
    },

    created() {
        const sections = [];
        _.forIn(this.$options.components, (component, name) => {
            if (component.section) {
                sections.push(_.extend({ name, priority: 0 }, component.section));
            }
        });
        this.$set(this, 'sections', _.sortBy(sections, 'priority'));
    },

    mounted() {
        const vm = this;
        this.tab = UIkit.tab('#area-tab', { connect: '#area-content' });

        UIkit.util.on(this.tab.connects, 'show', (e, tab) => {
            if (tab != vm.tab) return;
            for (const index in tab.toggles) {
                if (tab.toggles[index].classList.contains('uk-active')) {
                    vm.$session.set('docs.edit.tab.active', index);
                    vm.active = index;
                    break;
                }
            }
        });

        this.tab.show(this.active);
    },

    methods:{
        save(){
            this.$http.post('admin/docs/api/save' , {data:this.query , id:this.query.id}).then((res)=>{
                const {query} = res.data;
                if (!this.query.id) {
                    window.history.replaceState({}, '', this.$url.route('admin/docs/post/edit', { id: query.id }));
                }
                this.$set(this, 'query', query);
                this.$notify('Saved');
            }).catch((err)=>{
                this.$notify(err.bodyText , 'danger');
            })
        }
    },

    components:{
        Settings,
        Meta
    }
}

Vue.ready(window.Docs);