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
      <div class="shadow">
        <q-table
          :rows="questions"
          :columns="columns"
          row-key="id"
          flat
          :loading="loadingQuestions"
          :rows-per-page-options="[10, 20, 30]"
          no-data-label="No questions found"
          loading-label="Loading questions..."
          rows-per-page-label="Questions per page"
        >
          <template #body-cell-status="props">
            <q-td :props="props">
              <q-chip
                text-color="white"
                size="0.8rem"
                class="text-weight-medium"
                :label="props.row.is_active ? 'Active' : 'Inactive'"
                :color="props.row.is_active ? 'green-6' : 'red-6'"
              />
            </q-td>
          </template>

          <template #body-cell-actions="propsActions">
            <q-td auto-width :props="propsActions">

              <q-btn
                :icon="mdiChartBar"
                color="gray-btn"
                flat
                round
                :to="`/questions/${propsActions.row.code}/results`"
              >
                <q-tooltip content-style="font-size: 11px" :offset="[0, 4]"> Results </q-tooltip>
              </q-btn>

              <!-- Edit button -->
              <q-btn
                :icon="mdiPencil"
                color="gray-btn"
                flat
                round
                :to="`/questions/${propsActions.row.id}/edit`"
              >
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
      </div>
    </template>
  </PageLayout>
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import { ref } from 'vue'
import { toast } from 'vue3-toastify'
import type { QTableProps } from 'quasar'
import { mdiChartBar, mdiPencil, mdiPlus, mdiTrashCan } from '@quasar/extras/mdi-v7'
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
    name: 'status',
    label: 'Status',
    field: 'is_active',
    align: 'center',
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
