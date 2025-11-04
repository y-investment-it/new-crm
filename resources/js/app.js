import './bootstrap';
import '../css/app.css';
import { createApp, reactive } from 'vue';
import ReAssignLeads from './components/ReAssignLeads.vue';

const state = reactive({
    selectedIds: [],
});

const app = createApp({
    setup() {
        return { state };
    },
});

app.component('re-assign-leads', ReAssignLeads);

const mount = document.querySelector('#admin-app');
if (mount) {
    app.mount('#admin-app');
    window.adminApp = state;
}
