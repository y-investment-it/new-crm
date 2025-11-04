import { createApp, reactive, ref, onMounted, watch } from 'https://cdn.jsdelivr.net/npm/vue@3.3.4/dist/vue.esm-browser.prod.js';

const ReAssignLeads = {
    props: {
        leadIds: {
            type: Array,
            required: true,
        },
    },
    setup(props, { emit }) {
        const users = ref([]);
        const loading = ref(false);
        const submitting = ref(false);
        const form = reactive({
            user_id: null,
            type: 'fresh',
            duplicate: false,
            same_stage: false,
            salesman: false,
            clear_history: false,
        });

        const messages = {
            title: 'إعادة توزيع العملاء',
            selected: (count) => `عدد العملاء المحدد: ${count}`,
            loading: 'جاري التحميل...',
            assign_to: 'تعيين إلى',
            options: 'خيارات إضافية',
            duplicate: 'نسخ العميل كعميل جديد',
            same_stage: 'الاحتفاظ بنفس المرحلة',
            as_salesman: 'تعيين كمندوب مبيعات',
            clear_history: 'مسح السجل السابق',
            assign_type: 'نوع التعيين',
            type_fresh: 'عميل جديد',
            type_cold: 'مكالمة باردة',
            save: 'حفظ',
            saving: 'جاري الحفظ...',
            success: 'تم تحديث العملاء بنجاح',
            error: 'حدث خطأ أثناء إعادة التوزيع',
        };

        const fetchUsers = async () => {
            loading.value = true;
            try {
                const response = await fetch('/admin/users/list');
                users.value = await response.json();
                if (users.value.length && !form.user_id) {
                    form.user_id = users.value[0].id;
                }
            } finally {
                loading.value = false;
            }
        };

        const submit = async () => {
            if (!form.user_id) {
                alert('اختر مستخدماً.');
                return;
            }
            submitting.value = true;
            try {
                await fetch('/admin/leads/reassign', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''
                    },
                    body: JSON.stringify({
                        leads: props.leadIds,
                        user_id: form.user_id,
                        type: form.type,
                        duplicate: form.duplicate,
                        same_stage: form.same_stage,
                        salesman: form.salesman,
                        clear_history: form.clear_history,
                    }),
                });
                alert(messages.success);
                emit('updated');
            } catch (error) {
                console.error(error);
                alert(messages.error);
            } finally {
                submitting.value = false;
            }
        };

        onMounted(fetchUsers);

        watch(() => props.leadIds, (ids) => {
            if (!ids.length) {
                form.duplicate = false;
                form.same_stage = false;
                form.salesman = false;
                form.clear_history = false;
            }
        });

        return { users, loading, submitting, form, messages, submit };
    },
    template: `
      <div class="reassign-modal">
        <h2>{{ messages.title }}</h2>
        <p class="lead-count">{{ messages.selected(leadIds.length) }}</p>
        <div v-if="loading" class="loading">{{ messages.loading }}</div>
        <form v-else @submit.prevent="submit">
          <fieldset>
            <legend>{{ messages.assign_to }}</legend>
            <label v-for="user in users" :key="user.id" class="radio">
              <input type="radio" name="user" :value="user.id" v-model="form.user_id">
              <span>{{ user.name }} ({{ user.role }})</span>
            </label>
          </fieldset>
          <fieldset>
            <legend>{{ messages.options }}</legend>
            <label class="checkbox"><input type="checkbox" v-model="form.duplicate"> {{ messages.duplicate }}</label>
            <label class="checkbox"><input type="checkbox" v-model="form.same_stage"> {{ messages.same_stage }}</label>
            <label class="checkbox"><input type="checkbox" v-model="form.salesman"> {{ messages.as_salesman }}</label>
            <label class="checkbox"><input type="checkbox" v-model="form.clear_history"> {{ messages.clear_history }}</label>
          </fieldset>
          <fieldset>
            <legend>{{ messages.assign_type }}</legend>
            <label class="radio"><input type="radio" value="fresh" v-model="form.type"> {{ messages.type_fresh }}</label>
            <label class="radio"><input type="radio" value="cold_call" v-model="form.type"> {{ messages.type_cold }}</label>
          </fieldset>
          <div class="actions">
            <button type="submit" class="btn-primary" :disabled="submitting">{{ submitting ? messages.saving : messages.save }}</button>
          </div>
        </form>
      </div>
    `,
};

const state = reactive({ selectedIds: [] });

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
