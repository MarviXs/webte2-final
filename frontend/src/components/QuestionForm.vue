<template>
  <q-form ref="form" class="gap column" greedy>
    <div class="row gap-row items-center container-dashboard q-pa-lg">
      <q-select
        v-if="authStore.role === 'admin'"
        v-model="question.owner_id"
        :label="t('questions.owner')"
        bg-color="white"
        class="col-12"
        dense
        emit-value
        map-options
        clearable
        :options="ownerOptions"
      />
      <q-input class="col-grow" :label="t('questions.subject')" v-model="question.subject" />
      <q-checkbox dense v-model="question.is_active" :label="t('questions.active')" left-label />
    </div>
    <div class="container-dashboard q-pa-lg q-mt-lg">
      <div class="row gap-row items-center">
        <q-input
          autogrow
          class="col-grow"
          :label="t('questions.title')"
          v-model="question.question_text"
          :rules="[required()]"
        />
        <q-select
          :label="t('questions.type')"
          v-model="question.question_type"
          style="min-width: 200px"
          class="col-12 col-sm-auto"
          map-options
          :rules="[required()]"
          emit-value
          :options="questionTypes"
        >
          <template v-slot:selected-item="scope">
            <div class="row items-center">
              <q-icon size="1.25rem" color="grey-9" class="q-mr-sm" :name="scope.opt.icon" />
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
      </div>

      <ChoiceForm
        v-if="
          question.question_type === QuestionType.SINGLE_CHOICE ||
          question.question_type === QuestionType.MULTIPLE_CHOICE
        "
        v-model:local-choices="localChoices"
        v-model:choices="question.choices"
      />
    </div>
  </q-form>
</template>

<script setup lang="ts">
import type { QuestionRequest } from '@/models/Question'
import { QuestionType } from '@/models/Question'
import type { User } from '@/models/User'
import { useAuthStore } from '@/stores/auth-store'
import ChoiceForm from '@/components/ChoiceForm.vue'
import type { ChoiceRequest } from '@/models/Choice'
import { required } from '@/utils/form-validation'
import { computed, ref } from 'vue'
import { QForm } from 'quasar'
import { mdiCheckboxOutline, mdiRadioboxMarked, mdiText } from '@quasar/extras/mdi-v7'
import UserService from '@/services/UserService'
import { toast } from 'vue3-toastify'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const authStore = useAuthStore()

const userList = ref<User[]>([])
const ownerOptions = computed(() => {
  return userList.value.map((user) => ({ label: user.email, value: user.id }))
})

async function getAllUsers() {
  try {
    userList.value = await UserService.getUsers()
  } catch (error) {
    console.error(error)
    toast.error(t('toast.users.get_error'))
  }
}

if (authStore.role === 'admin') {
  getAllUsers()
}

const question = defineModel<QuestionRequest>('question', {
  required: true
})

const localChoices = defineModel<ChoiceRequest[]>('choices', {
  required: true
})

const questionTypes = [
  { label: t('questions.single_choice'), value: QuestionType.SINGLE_CHOICE, icon: mdiRadioboxMarked },
  { label: t('questions.multiple_choice'), value: QuestionType.MULTIPLE_CHOICE, icon: mdiCheckboxOutline },
  { label: t('questions.text'), value: QuestionType.OPEN, icon: mdiText }
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

.gap-row {
  gap: 2rem;
}
</style>
