<template>
  <PageLayout :title="t('main.vote')" max-width="900px">
    <div v-if="voteStore.question">
      <q-form greedy @submit="submitAnswer">
        <div class="container-dashboard q-pa-lg">
          <div class="question-text q-px-sm">
            {{ voteStore.question.question_text }}
          </div>
          <div
            v-if="voteStore.question.question_type === QuestionType.SINGLE_CHOICE"
            class="column"
          >
            <q-field
              :model-value="singleChoice"
              borderless
              for="none"
              color="black"
              :rules="[required()]"
              hide-bottom-space
            >
              <q-option-group v-model="singleChoice" :options="choiceOptions" color="primary" />
            </q-field>
          </div>
          <div
            v-else-if="voteStore.question.question_type === QuestionType.MULTIPLE_CHOICE"
            class="column"
          >
            <q-field
              :model-value="multipleChoice"
              borderless
              for="none"
              color="black"
              :rules="[requiredSingleOption()]"
              hide-bottom-space
            >
              <template #control>
                <q-option-group
                  v-model="multipleChoice"
                  :options="choiceOptions"
                  color="primary"
                  type="checkbox"
                />
              </template>
            </q-field>
          </div>

          <div
            v-else-if="voteStore.question.question_type === QuestionType.OPEN"
            class="column q-px-sm q-py-md"
          >
            <q-input v-model="openText" :label="t('vote.answear')" type="textarea" autogrow />
          </div>
        </div>
        <div class="q-mt-md">
          <q-btn
            class="shadow q-px-lg"
            color="primary"
            :label="t('questions.submit')"
            :loading="submitting"
            no-caps
            type="submit"
            unelevated
            size="15px"
          />
        </div>
      </q-form>
    </div>
    <div
      class="row full-width justify-center"
      v-else-if="!voteStore.question && voteStore.questionLoading"
    >
      <q-spinner size="32px" color="primary" />
    </div>
    <div v-else>{{ t('vote.data.code_not_found') }}</div>
  </PageLayout>
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import type { Answer } from '@/models/Answer'
import { QuestionType } from '@/models/Question'
import { useVoteStore } from '@/stores/vote-store'
import { required, requiredSingleOption } from '@/utils/form-validation'
import { computed } from 'vue'
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()


const voteStore = useVoteStore()
const route = useRoute()
const router = useRouter()

const singleChoice = ref<string>()
const multipleChoice = ref<string[]>([])
const openText = ref<string>('')

const choiceOptions = computed(() => {
  return voteStore.question?.choices.map((choice) => ({
    label: choice.choice_text,
    value: choice.id
  }))
})

function getQuestion() {
  if (route.params.code) {
    if (!voteStore.question || voteStore.question.code !== route.params.code) {
      voteStore.getQuestionByCode(route.params.code.toString())
    }
  }
}
getQuestion()

const submitting = ref(false)
async function submitAnswer() {
  submitting.value = true

  const allSelectedChoices = multipleChoice.value
  if (singleChoice.value) {
    allSelectedChoices.push(singleChoice.value)
  }

  const answer: Answer = {
    choices: allSelectedChoices,
    open_answer: openText.value
  }
  try {
    await voteStore.submitAnswer(answer)
    toast.success('Answer submitted successfully')
    router.push(`/${route.params.code}/results`)

  } catch (error) {
    toast.error('Error while submitting answer')
  } finally {
    submitting.value = false
  }
}
</script>

<style lang="scss" scoped>
.question-text {
  font-size: 1.15rem;
  font-weight: 500;
  margin-bottom: 1rem;
}

</style>
