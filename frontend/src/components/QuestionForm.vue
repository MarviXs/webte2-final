<template>
  <q-form ref="form" class="gap column" greedy>
    <q-checkbox dense v-model="question.is_active" label="Active" />
    <q-input class="q-mb-xs" label="Subject" v-model="question.subject" />
    <q-input
      autogrow
      label="Question Title"
      v-model="question.question_text"
      :rules="[required()]"
    />
    <q-select
      label="Question type"
      v-model="question.question_type"
      map-options
      emit-value
      :options="questionTypes"
    >
      <template v-slot:selected-item="scope">
        <div class="row items-center q-py-sm">
          <q-icon size="1.5rem" color="grey-9" class="q-mr-sm" :name="scope.opt.icon" />
          <div>{{ scope.opt.label }}</div>
        </div>
      </template>

      <template v-slot:option="scope">
        <q-item clickable="row items-center" v-bind="scope.itemProps">
          <q-item-section avatar>
            <q-icon class="q-mr-md" color="grey-9" size="1.5rem" :name="scope.opt.icon" />
          </q-item-section>
          <q-item-section>
            <q-item-label>{{ scope.opt.label }}</q-item-label>
          </q-item-section>
        </q-item>
      </template>
    </q-select>
    <ChoiceForm
      v-if="
        question.question_type === QuestionType.SINGLE_CHOICE ||
        question.question_type === QuestionType.MULTIPLE_CHOICE
      "
      v-model:localChoices="localChoices"
    />
  </q-form>
</template>

<script setup lang="ts">
import type { QuestionRequest } from '@/models/Question'
import { QuestionType } from '@/models/Question'
import ChoiceForm from '@/components/ChoiceForm.vue'
import type { ChoiceRequest } from '@/models/Choice'
import { required } from '@/utils/form-validation'
import { ref } from 'vue'
import { QForm } from 'quasar'
import { mdiCheckboxOutline, mdiRadioboxMarked, mdiText } from '@quasar/extras/mdi-v7'

const question = defineModel<QuestionRequest>('question', {
  required: true
})

const localChoices = defineModel<ChoiceRequest[]>('choices', {
  required: true
})

const questionTypes = [
  { label: 'Single choice', value: QuestionType.SINGLE_CHOICE, icon: mdiRadioboxMarked },
  { label: 'Multiple choice', value: QuestionType.MULTIPLE_CHOICE, icon: mdiCheckboxOutline },
  { label: 'Text', value: QuestionType.OPEN, icon: mdiText }
]

const form = ref<QForm>()
async function validate() {
  return await form.value?.validate()
}

defineExpose({ validate })
</script>

<style scoped>
.gap {
  gap: 0.5rem;
}
</style>
