<template>
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
        <label class="checkbox">
          <input type="checkbox" v-model="form.duplicate">
          <span>{{ messages.duplicate }}</span>
        </label>
        <label class="checkbox">
          <input type="checkbox" v-model="form.same_stage">
          <span>{{ messages.same_stage }}</span>
        </label>
        <label class="checkbox">
          <input type="checkbox" v-model="form.salesman">
          <span>{{ messages.as_salesman }}</span>
        </label>
        <label class="checkbox">
          <input type="checkbox" v-model="form.clear_history">
          <span>{{ messages.clear_history }}</span>
        </label>
      </fieldset>

      <fieldset>
        <legend>{{ messages.assign_type }}</legend>
        <label class="radio">
          <input type="radio" name="type" value="fresh" v-model="form.type">
          <span>{{ messages.type_fresh }}</span>
        </label>
        <label class="radio">
          <input type="radio" name="type" value="cold_call" v-model="form.type">
          <span>{{ messages.type_cold }}</span>
        </label>
      </fieldset>

      <div class="actions">
        <button type="submit" class="btn-primary" :disabled="submitting">{{ submitting ? messages.saving : messages.save }}</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from 'vue'

const props = defineProps({
  leadIds: {
    type: Array,
    required: true,
  },
})

const emit = defineEmits(['updated'])

const users = ref([])
const loading = ref(false)
const submitting = ref(false)

const form = reactive({
  user_id: null,
  type: 'fresh',
  duplicate: false,
  same_stage: false,
  salesman: false,
  clear_history: false,
})

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
}

const fetchUsers = async () => {
  loading.value = true
  try {
    const response = await fetch('/admin/users/list', {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
    })
    users.value = await response.json()
    if (users.value.length && !form.user_id) {
      form.user_id = users.value[0].id
    }
  } finally {
    loading.value = false
  }
}

const submit = async () => {
  if (!form.user_id) {
    alert('اختر مستخدماً.')
    return
  }
  submitting.value = true
  try {
    await fetch('/admin/leads/reassign', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN':
          document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
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
    })
    alert(messages.success)
    emit('updated')
  } catch (error) {
    console.error(error)
    alert(messages.error)
  } finally {
    submitting.value = false
  }
}

onMounted(fetchUsers)

watch(
  () => props.leadIds,
  (ids) => {
    if (!ids.length) {
      form.duplicate = false
      form.same_stage = false
      form.salesman = false
      form.clear_history = false
    }
  }
)
</script>

<style scoped>
.reassign-modal {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

fieldset {
  border: 1px solid rgba(0,0,0,0.1);
  padding: 1rem;
  border-radius: 0.75rem;
}

.legend {
  font-weight: 600;
}

.radio,
.checkbox {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
}

.actions {
  display: flex;
  justify-content: flex-end;
}
</style>
