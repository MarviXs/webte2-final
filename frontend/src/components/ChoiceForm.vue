<template>
  <div class="column gap">
    <template v-for="(choice, index) in choices" :key="index">
      <div class="row items-center">
        <q-input
          class="col-grow"
          :label="t('components.label') + (index + 1)"
          v-model="choice.choice_text"
        ></q-input>
        <q-btn
          color="red"
          round
          :icon="mdiClose"
          unelevated
          flat
          @click="() => deleteChoice(choice)"
        ></q-btn>
      </div>
    </template>

    <template v-for="(choice, localIndex) in localChoices" :key="localIndex + choices.length">
      <div class="row items-center">
        <q-input
          class="col-grow"
          :label="t('components.label') + (localIndex + 1 + choices.length)"
          v-model="choice.choice_text"
        ></q-input>
        <q-btn
          color="red"
          round
          :icon="mdiClose"
          unelevated
          flat
          @click="() => removeLocalChoice(localIndex)"
        ></q-btn>
      </div>
    </template>

    <q-btn class="q-mt-sm" :label="t('components.add_choice')" outline color="primary" @click="addChoice"></q-btn>
  </div>
</template>

<script setup lang="ts">
import type { Choice, ChoiceRequest } from '@/models/Choice'
import { mdiClose } from '@quasar/extras/mdi-v7'
import ChoiceService from '@/services/ChoiceService'
import { toast } from 'vue3-toastify'

const choices = defineModel<Choice[]>('choices', {
  required: true
})
const localChoices = defineModel<ChoiceRequest[]>('localChoices', {
  required: true
})

const addChoice = () => {
  localChoices.value.push({
    choice_text: '',
    order: choices.value.length + localChoices.value.length + 1
  })
  updateOrder()
}

const removeLocalChoice = (index: number) => {
  localChoices.value.splice(index, 1)
  updateOrder()
}

async function deleteChoice(choice: Choice) {
  try {
    await ChoiceService.deleteChoice(choice.id)
    choices.value = choices.value.filter((c) => c.id !== choice.id)
    updateOrder()
  } catch (error) {
    toast.error('Failed to delete choice')
  }
}

//Update the order of choices
function updateOrder() {
  const allChoices = [...choices.value, ...localChoices.value]
  allChoices.forEach((choice, index) => {
    choice.order = index + 1
  })
}
</script>

<style scoped>
.gap {
  gap: 0.5rem;
}
</style>
