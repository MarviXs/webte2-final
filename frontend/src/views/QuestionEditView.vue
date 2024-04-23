<template>
  <PageLayout
    title="Edit Question"
    previous-title="Questions"
    previous-route="/questions"
    class="full-width"
    max-width="900px"
  >
    <template #actions>
      <q-btn
        class="shadow"
        color="primary"
        label="Update"
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
    toast.error('Failed to fetch question')
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

    toast.success('Question updated successfully')
    router.push('/questions')
  } catch (error) {
    toast.error('Failed to update question')
  } finally {
    updatingQuestion.value = false
  }
}
</script>
