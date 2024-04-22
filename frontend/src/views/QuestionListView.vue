<template>
  <PageLayout title="Questions">
    <template #actions>
      <q-btn
        class="shadow"
        color="primary"
        :icon="mdiPlus"
        label="Create question"
        to="/questions/create"
        unelevated
        no-caps
        size="15px"
      />
    </template>
    <template #default>
      <q-table
        :rows="questions"
        :columns="columns"
        row-key="id"
        class="shadow"
        flat
        :loading="loadingQuestions"
        :rows-per-page-options="[10, 20, 30]"
        no-data-label="No questions found"
        loading-label="Loading questions..."
        rows-per-page-label="Questions per page"
      >
        <template #body-cell-actions="propsActions">
          <q-td auto-width :props="propsActions">
            <!-- Edit button -->
            <q-btn :icon="mdiPencil" color="gray-btn" flat round>
              <q-tooltip content-style="font-size: 11px" :offset="[0, 4]"> Edit </q-tooltip>
            </q-btn>

            <!-- Delete button -->
            <q-btn
              :icon="mdiTrashCan"
              color="gray-btn"
              flat
              round
              @click="() => deleteQuestion(propsActions.row.id)"
            >
              <q-tooltip content-style="font-size: 11px" :offset="[0, 4]"> Delete </q-tooltip>
            </q-btn>
          </q-td>
        </template>
      </q-table>
    </template>
  </PageLayout>
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import { ref } from 'vue'
import { toast } from 'vue3-toastify'
import type { QTableProps } from 'quasar'
import { mdiPencil, mdiPlus, mdiTrashCan } from '@quasar/extras/mdi-v7'
import type { Question } from '@/models/Question'
import QuestionService from '@/services/QuestionService'

const questions = ref<Question[]>([])

const loadingQuestions = ref(false)
async function getQuestions() {
  try {
    loadingQuestions.value = true
    questions.value = await QuestionService.getQuestions()
  } catch (error) {
    console.error(error)
    toast.error('Failed to load questions')
  } finally {
    loadingQuestions.value = false
  }
}
getQuestions()

async function deleteQuestion(id: string) {
  try {
    await QuestionService.deleteQuestion(id)
    toast.success('Question deleted successfully')
    getQuestions()
  } catch (error) {
    console.error(error)
    toast.error('Failed to delete question')
  }
}

const columns: QTableProps['columns'] = [
  {
    name: 'question_text',
    label: 'Question',
    field: 'question_text',
    align: 'left',
    sortable: true
  },
  {
    name: 'subject',
    label: 'Subject',
    field: 'subject',
    align: 'left',
    sortable: true
  },
  {
    name: 'code',
    label: 'Code',
    field: 'code',
    align: 'right',
    sortable: true
  },
  {
    name: 'actions',
    label: 'Actions',
    align: 'center',
    field: ''
  }
]
</script>

<style scoped></style>
