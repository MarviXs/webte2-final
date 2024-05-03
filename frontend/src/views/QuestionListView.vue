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
              <!-- Results button -->
              <q-btn
                :icon="mdiChartBar"
                color="gray-btn"
                flat
                round
                :to="`/questions/${propsActions.row.code}/results`"
              >
                <q-tooltip content-style="font-size: 11px" :offset="[0, 4]"> Results </q-tooltip>
              </q-btn>

              <!-- QR Code button -->
              <q-btn :icon="mdiQrcode" color="gray-btn" flat round @click="openQRCodeDialog(propsActions.row.code)">
                <q-tooltip content-style="font-size: 11px" :offset="[0, 4]"> QR Code </q-tooltip>
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

              <!--Dropdown -->
              <q-btn :icon="mdiDotsVertical" color="gray-btn" flat round>
                <q-menu anchor="bottom right" self="top right">
                  <q-list class="text-grey-10">
                    <q-item v-close-popup clickable @click="copyQuestion(propsActions.row.id)">
                      <div class="row items-center q-gutter-sm">
                        <q-icon color="grey-8" size="24px" :name="mdiContentCopy" />
                        <div>Copy</div>
                      </div>
                    </q-item>
                    <q-item v-close-popup clickable @click="deleteQuestion(propsActions.row.id)">
                      <div class="row items-center q-gutter-sm">
                        <q-icon color="grey-8" size="24px" :name="mdiTrashCan" />
                        <div>Delete</div>
                      </div>
                    </q-item>
                  </q-list>
                </q-menu>
              </q-btn>
            </q-td>
          </template>
        </q-table>
      </div>
    </template>
  </PageLayout>
  <QuestionQRCodeDialog v-model="qrCodeDialogOpen" :questionCode="qrCodeQuestionCode" />
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import { ref } from 'vue'
import { toast } from 'vue3-toastify'
import type { QTableProps } from 'quasar'
import {
  mdiChartBar,
  mdiContentCopy,
  mdiDotsVertical,
  mdiPencil,
  mdiPlus,
  mdiQrcode,
  mdiTrashCan
} from '@quasar/extras/mdi-v7'
import type { Question } from '@/models/Question'
import QuestionService from '@/services/QuestionService'
import QuestionQRCodeDialog from '@/components/QuestionQRCodeDialog.vue'

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

async function copyQuestion(id: string) {
  try {
    await QuestionService.copyQuestion(id)
    toast.success('Question copied successfully')
    getQuestions()
  } catch (error) {
    console.error(error)
    toast.error('Failed to copy question')
  }
}

const qrCodeQuestionCode = ref('')
const qrCodeDialogOpen = ref(false)
function openQRCodeDialog(questionCode: string) {
  qrCodeQuestionCode.value = questionCode
  qrCodeDialogOpen.value = true
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
