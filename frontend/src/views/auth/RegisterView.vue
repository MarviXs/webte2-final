<template>
  <div class="auth-bg">
    <div class="auth-container shadow">
      <h1>Register</h1>
      <div class="q-mt-md">
        <q-form greedy class="registerFormRef" @submit="register">
          <q-input
            ref="mailRef"
            v-model="userRegister.email"
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
            v-model="userRegister.first_name"
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
            v-model="userRegister.last_name"
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
            v-model="userRegister.password"
            label="Password"
            :type="isPwd ? 'password' : 'text'"
            :rules="[required(), minLength(6)]"
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
          <q-btn
            class="q-my-md full-width"
            color="primary"
            label="Register"
            type="submit"
            size="1rem"
            no-caps
            unelevated
            :loading="isSubmitting"
          />
        </q-form>
        <div class="column items-center links">
          <div class="q-md-md q-mt-lg">
            <span>{{ t('auth.register.have_account') }}</span>
            <router-link to="/login" class="q-ml-sm">
              {{ t('auth.register.login') }}
            </router-link>
          </div>
          <LanguageSelect></LanguageSelect>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import authService from '@/services/AuthService'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'
import { QForm, QInput } from 'quasar'
import { isEmail, required, maxLength, minLength } from '@/utils/form-validation'
import { mdiEmail, mdiEye, mdiEyeOff, mdiLock } from '@quasar/extras/mdi-v7'
import { useAuthStore } from '@/stores/auth-store'
import { mdiAccount } from '@quasar/extras/mdi-v7'
import type { UserRegister } from '@/models/Auth'
import { useI18n } from 'vue-i18n'
import LanguageSelect from '@/components/LanguageSelect.vue'

const router = useRouter()
const authStore = useAuthStore()
const { t } = useI18n()

const userRegister = ref<UserRegister>({
  email: '',
  first_name: '',
  last_name: '',
  password: ''
})
const isPwd = ref(true)
const isSubmitting = ref(false)

async function register() {
  try {
    isSubmitting.value = true
    authStore.token = ''
    const res = await authService.register(userRegister.value)
    authStore.token = res.token
    toast.success('Registrácia prebehla úspešne')
    router.push('/')
  } catch (error) {
    toast.error('Registrácia zlyhala')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style lang="scss" scoped>
auth-bg {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;

  @media screen and (max-width: 768px) {
    background-color: white;
  }

  .auth-container {
    width: 100%;
    max-width: 26rem;
    border-radius: 0.3rem;
    background-color: #ffffff;
    padding: 1rem 3rem;
    margin-top: -8vh;

    h1 {
      font-size: 1.6rem;
      font-weight: normal;
      margin: 1rem auto 0 auto;
      width: 100%;
      text-align: center;
      line-height: 1em;
    }

    .q-input {
      margin-top: 0.5rem;
      margin-bottom: 0.5rem;
    }

    @media screen and (max-width: 768px) {
      box-shadow: none;
      border: none;
      padding: 1rem 1.5rem;
      margin-top: 0;
    }
  }
}
</style>
