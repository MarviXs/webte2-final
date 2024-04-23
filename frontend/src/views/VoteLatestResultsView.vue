<template>
  <PageLayout title="Results" max-width="1200px">
    <template #actions>
      <q-btn
        class="shadow"
        color="primary"
        label="Vote history"
        :icon="mdiHistory"
        unelevated
        no-caps
        size="15px"
      />
    </template>
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
import { mdiHistory } from '@quasar/extras/mdi-v7'

const voteResult = ref<VoteResult>()
const route = useRoute()

async function getLatestResults() {
  const code = route.params.code.toString()
  try {
    voteResult.value = await VoteService.getLatestResults(code)
  } catch (error) {
    toast.error('Failed to get latest results')
  }
}
getLatestResults()
</script>


