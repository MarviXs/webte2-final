<template>
  <PageLayout
    :title="t('main.create_question')"
    previous-title="Questions"
    previous-route="/questions"
    class="full-width"
    max-width="900px"
  >
    <template #actions>
      <q-btn
        class="shadow"
        color="primary"
        :label="t('questions.create')"
        @click="createQuestion"
        :loading="creatingQuestion"
        type="submit"
        unelevated
        size="15px"
      />
    </template>
    <div>
      <QuestionForm ref="questionForm" v-model:question="question" v-model:choices="choices" />
    </div>
  </PageLayout>
</template>

<script setup lang="ts">
import QuestionForm from '@/components/QuestionForm.vue'
import PageLayout from '@/layouts/PageLayout.vue'
import type { ChoiceRequest } from '@/models/Choice'
import type { QuestionRequest } from '@/models/Question'
import { QuestionType } from '@/models/Question'
import QuestionService from '@/services/QuestionService'
import ChoiceService from '@/services/ChoiceService'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue3-toastify'
import { useI18n } from 'vue-i18n';
const { t } = useI18n()

const router = useRouter()

const question = ref<QuestionRequest>({
  question_text: '',
  question_type: QuestionType.SINGLE_CHOICE,
  choices: [],
  is_active: true,
  owner_id: undefined
})

const choices = ref<ChoiceRequest[]>([{ choice_text: '', order: 1 }])

const questionForm = ref()

const creatingQuestion = ref(false)
async function createQuestion() {
  if ((await questionForm.value.validate()) === false) {
    return
  }
  creatingQuestion.value = true
  try {
    const createdQuestion = await QuestionService.createQuestion(question.value)

    const validChoices = choices.value.filter((choice) => choice.choice_text.trim() !== '')
    const choiceCreationPromises = validChoices.map((choice) =>
      ChoiceService.createChoice(choice, createdQuestion.id)
    )
    await Promise.all(choiceCreationPromises)

    toast.success('Question created successfully')
    router.push('/questions')
  } catch (error) {
    toast.error('Failed to create question')
  } finally {
    creatingQuestion.value = false
  }
}
</script>
