<template>
  <PageLayout>
    <div class="full-width row items-center justify-center q-pt-lg">
      <q-form
        greedy
        @submit="submitCode"
        class="column col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3 q-pa-lg container-dashboard"
      >
        <q-input
          v-model="voteCode"
          label="Vote code"
          type="text"
          :rules="[required(), minLength(5), maxLength(5)]"
          lazy-rules
        >
          <template #prepend>
            <q-icon :name="mdiPound" />
          </template>
        </q-input>
        <q-btn
          class="q-my-md"
          color="primary"
          label="Next"
          type="submit"
          size="1rem"
          :loading="voteStore.questionLoading"
          no-caps
          :icon-right="mdiArrowRight"
          unelevated
        />
      </q-form>
    </div>
  </PageLayout>
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import { ref } from 'vue'
import { required, minLength, maxLength } from '@/utils/form-validation'
import { mdiArrowRight, mdiPound } from '@quasar/extras/mdi-v7'
import { useVoteStore } from '@/stores/vote-store';
import { toast } from 'vue3-toastify';
import { useRouter } from 'vue-router';

const voteCode = ref('')
const voteStore = useVoteStore()
const router = useRouter()

async function submitCode() {
  try {
    await voteStore.getQuestionByCode(voteCode.value)
    router.push(`/${voteStore.question?.code}`)
  }
  catch (error) {
    toast.error('Question not found')
  }
  
}
</script>

<style scoped></style>
