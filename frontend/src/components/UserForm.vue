<template>
  <q-form ref="form" class="gap column container-dashboard q-pa-lg" greedy>
    <q-input
      ref="mailRef"
      v-model="user.email"
      :label="t('auth.email')"
      type="email"
      :rules="[required(), isEmail()]"
      lazy-rules
    >
      <template #prepend>
        <q-icon :name="mdiEmail" />
      </template>
    </q-input>
    <q-input
      v-model="user.first_name"
      :label="t('users.first_name')"
      type="text"
      :rules="[required(), minLength(2), maxLength(50)]"
      lazy-rules
    >
      <template #prepend>
        <q-icon :name="mdiAccount" />
      </template>
    </q-input>
    <q-input
      v-model="user.last_name"
      :label="t('users.last_name')"
      type="text"
      :rules="[required(), minLength(2), maxLength(80)]"
      lazy-rules
    >
      <template #prepend>
        <q-icon :name="mdiAccount" />
      </template>
    </q-input>
    <q-input
      ref="passwordRef"
      v-model="user.password"
      :label="t('auth.password')"
      :type="isPwd ? 'password' : 'text'"
    >
      <template #prepend>
        <q-icon :name="mdiLock" />
      </template>
      <template #append>
        <q-icon
          :name="isPwd ? mdiEyeOff : mdiEye"
          class="cursor-pointer"
          @click="isPwd = !isPwd"
          :rules="[required(), minLength(8)]"
        />
      </template>
    </q-input>
    <q-select
      :label="t('users.roles.role')"
      v-model="user.role"
      style="min-width: 200px"
      class="col-12 col-sm-auto"
      map-options
      :rules="[required()]"
      emit-value
      :options="roles"
    >
      <template v-slot:selected-item="scope">
        <div class="row items-center">
          <q-icon size="1.25rem" color="grey-9" class="q-mr-sm" :name="scope.opt.icon" />
          <div>{{ scope.opt.label }}</div>
        </div>
      </template>

      <template v-slot:option="scope">
        <q-item clickable="row items-center" v-bind="scope.itemProps">
          <q-item-section avatar>
            <q-icon class="q-mr-md" color="grey-9" size="1.5rem" :name="scope.opt.icon" />
          </q-item-section>
          <q-item-section>
            <q-item-label>{{ scope.opt.label }}</q-item-label>
          </q-item-section>
        </q-item>
      </template>
    </q-select>
  </q-form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { QForm, QInput } from 'quasar'
import { isEmail, required, minLength, maxLength } from '@/utils/form-validation'
import { mdiEmail } from '@quasar/extras/mdi-v7'
import {
  mdiAccount,
  mdiShieldAccountOutline,
  mdiLock,
  mdiEyeOff,
  mdiEye
} from '@quasar/extras/mdi-v7'
import { type UserCreate } from '@/models/User'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const user = defineModel<UserCreate>('user', {
  required: true
})

const roles = [
  { label: t('users.roles.user'), value: 'user', icon: mdiAccount },
  { label: t('users.roles.admin'), value: 'admin', icon: mdiShieldAccountOutline }
]

const isPwd = ref(true)

const form = ref<QForm>()
async function validate() {
  return await form.value?.validate()
}

defineExpose({ validate })
</script>

<style scoped>
.gap {
  gap: 0.5rem;
}

.gap-row {
  gap: 2rem;
}
</style>
