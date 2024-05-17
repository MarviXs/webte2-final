<template>
  <PageLayout :title="t('main.guide')" max-width="1200px">
    <template #actions>
      <q-btn class="shadow" color="primary" :label="t('questions.export')" unelevated size="15px" @click="exportGuide" />
    </template>
    <template #default>
      <div id="guide-export" class="text-justify">
        <h3>{{ t('guide.title') }}</h3>
        <p>{{ t('guide.text') }}</p>
        <h4>{{ t('guide.content.login_register.title') }}</h4>
        <p>{{ t('guide.content.login_register.text') }}</p>
        <h4>{{ t('guide.content.voting.title') }}</h4>
        <p>{{ t('guide.content.voting.text') }}</p>
        <h4>{{ t('guide.content.guest.title') }}</h4>
        <p>{{ t('guide.content.guest.text') }}</p>
        <h4>{{ t('guide.content.user.title') }}</h4>
        <p>{{ t('guide.content.user.text') }}</p>
          <br>
        <h5>{{ t('guide.content.questions.title') }}</h5>
        <p>{{ t('guide.content.questions.text') }}</p>
        <h6>{{ t('guide.content.results.title') }}</h6>
        <p>{{ t('guide.content.results.text') }}</p>
        <h4>{{ t('guide.content.admin.title') }}</h4>
        <p>{{ t('guide.content.admin.text') }} <br></p>
        <h5>{{ t('guide.content.questions_admin.title') }}</h5>
        <p>{{ t('guide.content.questions_admin.text') }}</p>
        <h4>{{ t('guide.content.other.title') }}</h4>
        <ul class="text-left">
          <li>{{ t('guide.content.other.list.item1') }}</li>
          <li>{{ t('guide.content.other.list.item2') }}</li>
          <li>{{ t('guide.content.other.list.item3') }}</li>
        </ul>
      </div>
    </template>
  </PageLayout>
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import { useI18n } from 'vue-i18n'
import html2pdf from 'html2pdf.js'

const { t } = useI18n()

const exportGuide = () => {
  const element = document.getElementById('guide-export')
  html2pdf(element, {
    margin: 1,
    filename: 'guide.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  })
}

</script>
