<template>
    <q-form ref="form" class="gap column" greedy>
            <q-input
            ref="mailRef"
            v-model="user.email"
            label="Email"
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
            label="First name"
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
            label="Last name"
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
            label="Password"
            :type="isPwd ? 'password' : 'text'"
            :rules="[ required(), minLength(6)]"
          >
            <template #prepend>
              <q-icon :name="mdiLock" />
            </template>
            <template #append>
              <q-icon
                :name="isPwd ? mdiEyeOff : mdiEye"
                class="cursor-pointer"
                @click="isPwd = !isPwd"
              />
            </template>
          </q-input>
            <q-select
                :label="t('user.role')"
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
import { mdiAccount, mdiShieldAccountOutline, mdiLock, mdiEyeOff, mdiEye } from '@quasar/extras/mdi-v7'
import  type { UserUpdate } from '@/models/Auth'
import { useI18n } from 'vue-i18n'
import LanguageSelect from '@/components/LanguageSelect.vue'

const { t } = useI18n()

const user = defineModel<UserUpdate>('user', {
  required: true
})

const roles = [
  { label: "user", value: 'user', icon: mdiAccount },
  { label: "admin", value: 'admin', icon: mdiShieldAccountOutline }
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
