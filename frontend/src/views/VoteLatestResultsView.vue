<template>
  <PageLayout title="Results" previous-title="Questions" previous-route="/questions">
    <template #actions>
      <q-btn
        class="shadow bg-white"
        color="white"
        text-color="primary"
        label="Vote Archive"
        :icon="mdiHistory"
        :to="`/${route.params.code}/history`"
        outline
        no-caps
        size="15px"
      />
      <q-btn
        class="shadow"
        color="primary"
        label="Close Vote"
        @click="closeVoteDialog = true"
        :icon="mdiCloseCircleOutline"
        unelevated
        no-caps
        size="15px"
      />
    </template>
    <VoteResultsCard v-if="voteResult" :voteResult="voteResult" />
  </PageLayout>

  <q-dialog v-model="closeVoteDialog">
    <q-card style="width: 300px">
      <q-card-section>
        <div class="text-h6">Close Vote</div>
      </q-card-section>
      <q-card-section>
        <q-input
          v-model="closeVoteNote"
          label="Note"
          type="textarea"
          outlined
        />
      </q-card-section>
      <q-card-actions align="right">
        <q-btn label="Cancel" color="primary" flat @click="closeVoteDialog = false" />
        <q-btn label="Close vote" color="primary" unelevated @click="closeVote" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import type { VoteResult } from '@/models/VoteResult'
import VoteService from '@/services/VoteService'
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { toast } from 'vue3-toastify'
import VoteResultsCard from '@/components/VoteResultsCard.vue'
import { mdiCloseCircleOutline, mdiHistory } from '@quasar/extras/mdi-v7'

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

const closeVoteNote = ref('')
const closeVoteDialog = ref(false)

async function closeVote() {
  const code = route.params.code.toString()
  try {
    await VoteService.closeVote(code, closeVoteNote.value)
    toast.success('Vote closed successfully')
    closeVoteDialog.value = false
    getLatestResults()
  } catch (error) {
    toast.error('Failed to close vote')
  }
}

</script>
