<template>
  <div class="row justify-center">
    <PageLayout title="Create Question" class="full-width" style="max-width: 900px">
      <template #actions>
        <q-btn
          class="shadow"
          color="primary"
          label="Create"
          @click="createQuestion"
          :loading="creatingQuestion"
          type="submit"
          unelevated
          size="15px"
        />
      </template>
      <div class="q-pa-lg container-dashboard">
        <QuestionForm ref="questionForm" v-model:question="question" v-model:choices="choices" />
      </div>
    </PageLayout>
  </div>
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

const router = useRouter()

const question = ref<QuestionRequest>({
  question_text: '',
  question_type: QuestionType.SINGLE_CHOICE,
  is_active: true
})

const choices = ref<ChoiceRequest[]>([{ choice_text: '' }])

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
