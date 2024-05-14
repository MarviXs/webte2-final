<template>
  <PageLayout :title="t('vote.results')" :previous-title="t('main.questions')"  previous-route="/questions">
    <template #actions>
      <q-btn
        class="shadow bg-white"
        color="white"
        text-color="primary"
        :label="t('vote.vote_archive')"
        :icon="mdiHistory"
        :to="`/${route.params.code}/history`"
        outline
        no-caps
        size="15px"
      />
      <q-btn
        class="shadow"
        color="primary"
        :label="t('vote.close_vote')"
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
        <div class="text-h6">{{ t('vote.close_vote') }}</div>
      </q-card-section>
      <q-card-section>
        <q-input
          v-model="closeVoteNote"
          :label="t('columns.note')"
          type="textarea"
          outlined
        />
      </q-card-section>
      <q-card-actions align="right">
        <q-btn :label="t('questions.QR.cancel')" color="primary" flat @click="closeVoteDialog = false" />
        <q-btn :label="t('vote.close_vote')" color="primary" unelevated @click="closeVote" />
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

const closeVoteNote = ref('')
const closeVoteDialog = ref(false)

async function closeVote() {
  const code = route.params.code.toString()
  try {
    await VoteService.closeVote(code, closeVoteNote.value)
    toast.success(t('toast.questions.close'))
    closeVoteDialog.value = false
    getLatestResults()
  } catch (error) {
    toast.error(t('toast.questions.close_error'))
  }
}

</script>
