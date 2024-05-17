<template>
  <PageLayout
    :title="t('questions.edit')"
    :previous-title="t('questions.previouse_title')"
    previous-route="/questions"
    class="full-width"
    max-width="900px"
  >
    <template #actions>
      <q-btn
        class="shadow"
        color="primary"
        :label="t('questions.update')"
        @click="updateQuestion"
        :loading="updatingQuestion"
        type="submit"
        unelevated
        size="15px"
      />
    </template>
    <div v-if="question">
      <QuestionForm ref="questionForm" v-model:question="question" v-model:choices="localChoices" />
    </div>
  </PageLayout>
</template>

<script setup lang="ts">
import QuestionForm from '@/components/QuestionForm.vue'
import PageLayout from '@/layouts/PageLayout.vue'
import type { ChoiceRequest } from '@/models/Choice'
import type { Question } from '@/models/Question'
import QuestionService from '@/services/QuestionService'
import ChoiceService from '@/services/ChoiceService'
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'
import { useI18n } from 'vue-i18n';
const { t } = useI18n()

const router = useRouter()
const route = useRoute()

const question = ref<Question>()
const localChoices = ref<ChoiceRequest[]>([])

async function getQuestion() {
  const questionId = route.params.id.toString()
  try {
    const questionData = await QuestionService.getQuestion(questionId)
    question.value = questionData
  } catch (error) {
    toast.error(t('toast.questions.get_error'))
    router.push('/questions')
  }
}
getQuestion()

const questionForm = ref()
const updatingQuestion = ref(false)
async function updateQuestion() {
  if ((await questionForm.value.validate()) === false) {
    return
  }
  if (!question.value) {
    return
  }

  updatingQuestion.value = true
  try {
    const editedQuestion = await QuestionService.updateQuestion(question.value.id, question.value)
    const validChoices = localChoices.value.filter((choice) => choice.choice_text.trim() !== '')

    const choiceCreationPromises = validChoices.map((choice) =>
      ChoiceService.createChoice(choice, editedQuestion.id)
    )
    await Promise.all(choiceCreationPromises)

    toast.success(t('toast.questions.update'))
    router.push('/questions')
  } catch (error) {
    toast.error(t('toast.questions.update_error'))
  } finally {
    updatingQuestion.value = false
  }
}
</script>
