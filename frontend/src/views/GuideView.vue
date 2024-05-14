<template>
  <PageLayout :title="t('main.guide')" max-width="1200px">
    <template #actions>
      <q-btn class="shadow" color="primary" :label="t('questions.export')" unelevated size="15px" />
    </template>
    <template #default>
      {{ t('todo') }}
      <!-- TODO GUIDE -->
    </template>
  </PageLayout>
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>
