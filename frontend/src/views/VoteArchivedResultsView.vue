<template>
  <PageLayout title="Results" previous-title="Vote Archive" :previous-route="`/${route.params.code}/history`">
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

const voteResult = ref<VoteResult>()
const route = useRoute()

async function getArchivedResults() {
  const code = route.params.code.toString()
  const closureId = route.params.closure_id.toString()

  try {
    voteResult.value = await VoteService.getArchivedResults(code, closureId)
  } catch (error) {
    toast.error('Failed to get latest results')
  }
}
getArchivedResults()
</script>
