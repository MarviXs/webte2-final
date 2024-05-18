<template>
  <div class="auth-bg">
    <div class="auth-container shadow">
      <h1>{{ t('auth.login.label') }}</h1>
      <div class="q-mt-lg">
        <q-form greedy @submit="login">
          <q-input
            ref="nameRef"
            v-model="userLogin.email"
            :label="t('auth.email')"
            type="text"
            :rules="[required(), isEmail()]"
            lazy-rules
          >
            <template #prepend>
              <q-icon :name="mdiAccount" />
            </template>
          </q-input>
          <q-input
            ref="passwordRef"
            v-model="userLogin.password"
            :label="t('auth.password')"
            :type="isPwd ? 'password' : 'text'"
            :rules="[required()]"
            lazy-rules
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
            :label="t('auth.login.login_btn')"
            type="submit"
            size="1rem"
            no-caps
            unelevated
            :loading="isSubmitting"
          />
        </q-form>
        <div class="column items-center links">
          <div class="q-md-md q-mt-lg">
            <span>{{ t('auth.login.no_account') }}</span>
            <router-link to="/register" class="q-ml-sm">
              {{ t('auth.login.sign_up') }}
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
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'
import { useAuthStore } from '@/stores/auth-store'
import { required, isEmail } from '@/utils/form-validation'
import { useI18n } from 'vue-i18n'
import { mdiAccount, mdiEye, mdiEyeOff, mdiLock } from '@quasar/extras/mdi-v7'
import type { UserLogin } from '@/models/Auth'
import type { QInput } from 'quasar'
import LanguageSelect from '@/components/LanguageSelect.vue'

const { t } = useI18n()

const router = useRouter()
const authStore = useAuthStore()

const userLogin = ref<UserLogin>({
  email: '',
  password: ''
})

const isPwd = ref(true)
const isSubmitting = ref(false)

async function login() {
  try {
    isSubmitting.value = true
    await authStore.login(userLogin.value)
    toast.success(t('auth.login.toasts.login_success'))
    router.push('/')
  } catch (error) {
    toast.error(t('auth.login.toasts.login_failed'))
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
