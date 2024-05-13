<template>
    <PageLayout :title="t('account.label')">
      <template #default>
        <span>{{ t('account.change_password') }}</span>
        <router-link to="/change-password" class="q-ml-sm">
          {{ t('auth.change_password.label') }}
        </router-link>

        <div v-if="authStore.role === 'admin'">
          <span>{{ t('account.manage_users') }}</span>
          <router-link to="/users" class="q-ml-sm">
            {{ t('users.manage_users') }}
          </router-link>
        </div>
      </template>
    </PageLayout>
  </template>
  
  <script setup lang="ts">
  import PageLayout from '@/layouts/PageLayout.vue'
  import { useI18n } from 'vue-i18n'
  import { useAuthStore } from '@/stores/auth-store';
  const { t } = useI18n()

  const authStore = useAuthStore()

  </script>
  