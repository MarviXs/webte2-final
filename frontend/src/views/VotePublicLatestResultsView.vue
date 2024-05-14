<template>
  <PageLayout :title="t('vote.results')">
    <VoteResultsCard v-if="voteResult" :voteResult="voteResult" />
  </PageLayout>
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import type { VoteResult } from '@/models/VoteResult'
import VoteService from '@/services/VoteService'
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { toast } from 'vue3-toastify'
import VoteResultsCard from '@/components/VoteResultsCard.vue'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()


const voteResult = ref<VoteResult>()
const route = useRoute()

async function getLatestResults() {
  const code = route.params.code.toString()
  try {
    voteResult.value = await VoteService.getLatestResults(code)
  } catch (error) {
    toast.error(t('toast.result.get_error'))
  }
}
getLatestResults()
</script>


