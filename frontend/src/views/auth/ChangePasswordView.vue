<template>
    <div class="auth-bg">
      <div class="auth-container shadow">
        <h1>{{ t('auth.change_password.label') }}</h1>
        <div class="q-mt-md">
          <q-form greedy class="registerFormRef" @submit="changePassword">
            <q-input
              ref="passwordRef"
              v-model="userChangePassword.current_password"
              label="Old Password"
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
            <q-input
              ref="passwordRef"
              v-model="userChangePassword.new_password"
              label="New Password"
              :type="isPwdNew ? 'password' : 'text'"
              :rules="[required(), minLength(6)]"
            >
              <template #prepend>
                <q-icon :name="mdiLock" />
              </template>
              <template #append>
                <q-icon
                  :name="isPwdNew ? mdiEyeOff : mdiEye"
                  class="cursor-pointer"
                  @click="isPwdNew = !isPwdNew"
                />
              </template>
            </q-input>
            <q-btn
              class="q-my-md full-width"
              color="primary"
              :label="t('auth.change_password.change_password_btn')"
              type="submit"
              size="1rem"
              no-caps
              unelevated
              :loading="isSubmitting"
            />
          </q-form>
          <div class="column items-center links">
            <div class="q-md-md q-mt-lg">
              <router-link to="/account" class="q-ml-sm">
                {{ t('auth.change_password.back') }}
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
  import { required, minLength } from '@/utils/form-validation'
  import { mdiEye, mdiEyeOff, mdiLock } from '@quasar/extras/mdi-v7'
  import type { UserChangePassword } from '@/models/Auth'
  import { useI18n } from 'vue-i18n'
  import LanguageSelect from '@/components/LanguageSelect.vue'
  
  const router = useRouter()
  const { t } = useI18n()
  
  const userChangePassword = ref<UserChangePassword>({
    current_password: '',
    new_password: '',
  })
  const isPwd = ref(true)
  const isPwdNew = ref(true)
  const isSubmitting = ref(false)
  
  async function changePassword() {
    try {
      isSubmitting.value = true
      await authService.changePassword(userChangePassword.value)
      toast.success('Zmena hesla prebehla úspešne')
      router.push('/')
    } catch (error) {
      toast.error('Nastala chyba pri zmene hesla')
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
  