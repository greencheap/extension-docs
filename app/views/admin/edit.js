import Settings from '../../components/post-edit-settings.vue';
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

    components:{
        Settings
    }
}

Vue.ready(window.Docs);