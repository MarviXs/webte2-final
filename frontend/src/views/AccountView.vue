<template>
    <PageLayout title="Account">
      <template #default>
        <span>Want to change your password?</span>
        <router-link to="/change-password" class="q-ml-sm">
          {{ t('auth.change_password.label') }}
        </router-link>
      </template>
    </PageLayout>
  </template>
  
  <script setup lang="ts">
  import PageLayout from '@/layouts/PageLayout.vue'
  import { useI18n } from 'vue-i18n'

  const { t } = useI18n()
  </script>
  