<template>
  <div class="column gap">
    <template v-for="(choice, index) in localChoices" :key="index">
      <div class="row items-center">
        <q-input
          class="col-grow"
          :label="'Choice ' + (index + 1)"
          v-model="choice.choice_text"
        ></q-input>
        <q-btn
          color="red"
          round
          :icon="mdiClose"
          unelevated
          flat
          @click="() => removeChoice(index)"
        ></q-btn>
      </div>
    </template>
    <q-btn class="q-mt-sm" label="Add Choice" outline color="primary" @click="addChoice"></q-btn>
  </div>
</template>

<script setup lang="ts">
import type { ChoiceRequest } from '@/models/Choice'
import { mdiClose } from '@quasar/extras/mdi-v7'

const localChoices = defineModel<ChoiceRequest[]>('localChoices', {
  required: true
})

const addChoice = () => {
  localChoices.value.push({
    choice_text: ''
  })
}

const removeChoice = (index: number) => {
  localChoices.value.splice(index, 1)
}
</script>

<style scoped>
.gap {
  gap: 0.5rem;
}

</style>
