<template>
  <PageLayout :title="t('main.questions')">
    <template #actions>
      <q-input
        dense
        filled
        :label="t('main.filter')"
        class="bg-white shadow"
        bg-color="white"
        :model-value="
          filterDate.from || filterDate.to ? `${filterDate.from} - ${filterDate.to}` : ''
        "
        @click="dateProxy.show()"
      >
        <template #append>
          <q-icon :name="mdiCalendar" class="cursor-pointer">
            <q-popup-proxy cover transition-show="scale" transition-hide="scale" ref="dateProxy">
              <q-date range v-model="filterDate">
                <div class="row items-center justify-end">
                  <q-btn v-close-popup :label="t('questions.close')" color="primary" flat />
                </div>
              </q-date>
            </q-popup-proxy>
          </q-icon>
          <q-icon
            :name="mdiCloseCircle"
            class="cursor-pointer"
            v-if="filterDate.from || filterDate.to"
            @click="filterDate = { from: '', to: '' }"
          >
          </q-icon>
        </template>
      </q-input>
      <q-select
        v-model="filterSubject"
        :label="t('questions.subject')"
        filled
        bg-color="white"
        class="shadow"
        dense
        emit-value
        map-options
        clearable
        :options="filterSubjectOptions"
        style="min-width: 200px"
      />
      <q-select
        v-if="authStore.role === 'admin'"
        v-model="filterUser"
        :label="t('users.roles.upper_user')"
        filled
        bg-color="white"
        class="shadow"
        dense
        emit-value
        map-options
        clearable
        :options="filterUserOptions"
        style="min-width: 200px"
      />
      <q-btn
        class="shadow"
        color="primary"
        :icon="mdiPlus"
        :label="t('main.create_question')"
        to="/questions/create"
        unelevated
        no-caps
        size="15px"
      />
      <q-btn
        class="shadow"
        color="primary"
        :icon="mdiContentCopy"
        :label="t('questions.export')"
        @click="exportQuestions"
        unelevated
        no-caps
        size="15px"
      />
    </template>
    <template #default>
      <div class="shadow">
        <q-table
          :rows="filteredQuestions"
          :columns="columns"
          row-key="id"
          flat
          :loading="loadingQuestions"
          :rows-per-page-options="[10, 20, 30]"
          :no-data-label="t('vote.data.data_label')"
          :loading-label="t('vote.data.loading_label')"
          :rows-per-page-label="t('vote.data.rows_label')"
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
              <q-btn
                :icon="mdiQrcode"
                color="gray-btn"
                flat
                round
                @click="openQRCodeDialog(propsActions.row.code)"
              >
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
                        <div>{{ t('questions.delete') }}</div>
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
  mdiCalendar,
  mdiChartBar,
  mdiCloseCircle,
  mdiContentCopy,
  mdiDotsVertical,
  mdiPencil,
  mdiPlus,
  mdiQrcode,
  mdiTrashCan
} from '@quasar/extras/mdi-v7'
import type { Question } from '@/models/Question'
import type { VoteResult } from '@/models/VoteResult'
import type { User } from '@/models/User'
import QuestionService from '@/services/QuestionService'
import VoteService from '@/services/VoteService'
import UserService from '@/services/UserService'
import { useAuthStore } from '@/stores/auth-store'
import QuestionQRCodeDialog from '@/components/QuestionQRCodeDialog.vue'
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t, locale } = useI18n()
const authStore = useAuthStore()
const users = ref<User[]>([])
const questions = ref<Question[]>([])
let voteResult = ref<VoteResult>()

const filteredQuestions = computed(() => {
  if (!filterSubject.value && !filterDate.value.from && !filterDate.value.to && !filterUser.value) {
    return questions.value
  }

  return questions.value.filter((question) => {
    const createdAt = new Date(question.created_at * 1000)
    const from = filterDate.value.from ? new Date(filterDate.value.from) : null
    const to = filterDate.value.to ? new Date(filterDate.value.to) : null

    return (
      (!filterSubject.value || question.subject === filterSubject.value) &&
      (!from || createdAt >= from) &&
      (!to || createdAt <= to) &&
      (authStore.role === 'admin'
        ? !filterUser.value || question.owner?.id === filterUser.value
        : true)
    )
  })
})

async function getAllUsers() {
  try {
    users.value = await UserService.getUsers()
  } catch (error) {
    console.error(error)
    toast.error('Failed to load users')
  }
}

const loadingQuestions = ref(false)
async function getQuestions() {
  try {
    loadingQuestions.value = true
    if (authStore.role === 'admin') {
      getAllUsers()
      questions.value = await QuestionService.getQuestionsAdmin()
    } else {
      questions.value = await QuestionService.getQuestions()
    }
  } catch (error) {
    console.error(error)
    toast.error('Failed to load questions')
  } finally {
    loadingQuestions.value = false
  }
}
getQuestions()
async function getAnswers(code: string) {
  try {
    voteResult.value = await VoteService.getLatestResults(code)
    return voteResult.value.answers
  } catch (error) {
    toast.error('Failed to get latest results')
  }
}

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

const filterSubject = ref('')
const filterSubjectOptions = computed(() => {
  return questions.value
    .map((question) => question.subject)
    .filter((subject, index, self) => self.indexOf(subject) === index)
    .map((subject) => ({ label: subject, value: subject }))
})
const filterUser = ref('')
const filterUserOptions = computed(() => {
  return users.value.map((user) => ({ label: user.email, value: user.id }))
})

const filterDate = ref({ from: '', to: '' })
const dateProxy = ref()

const qrCodeQuestionCode = ref('')
const qrCodeDialogOpen = ref(false)
function openQRCodeDialog(questionCode: string) {
  qrCodeQuestionCode.value = questionCode
  qrCodeDialogOpen.value = true
}

const columns: QTableProps['columns'] = [
  {
    name: 'code',
    label: t('columns.code'),
    field: 'code',
    align: 'left',
    sortable: true
  },
  {
    name: 'question_text',
    label: t('columns.question'),
    field: 'question_text',
    align: 'left',
    sortable: true
  },
  {
    name: 'subject',
    label: t('questions.subject'),
    field: 'subject',
    align: 'left',
    sortable: true
  },
  {
    name: 'status',
    label: t('columns.status'),
    field: 'is_active',
    align: 'center',
    sortable: true
  },
  {
    name: 'created_at',
    label:  t('columns.created_at'),
    field: 'created_at',
    align: 'right',
    format(val) {
      return new Date(val * 1000).toLocaleDateString(locale.value)
    },
    sortable: true
  },
  {
    name: 'updated_at',
    label:  t('columns.updated_at'),
    field: 'updated_at',
    align: 'right',
    format(val) {
      return new Date(val * 1000).toLocaleDateString(locale.value)
    },
    sortable: true
  },
  {
    name: 'actions',
    label:  t('columns.actions'),
    align: 'center',
    field: ''
  }
]

// Export questions in a json file on button click
function exportQuestions() {
  //Create a json object with question name and answers
  const exportQuestions = async () => {
    try {
      const questionsWithAnswers = await Promise.all(
        questions.value.map(async (question) => {
          const answers = await getAnswers(question.code)
          return { ...question, answers }
        })
      )

      const json = JSON.stringify(questionsWithAnswers, null, 2)
      const blob = new Blob([json], { type: 'application/json' })
      const url = URL.createObjectURL(blob)
      const link = document.createElement('a')
      link.href = url
      link.download = 'questions.json'
      link.click()
      URL.revokeObjectURL(url)
    } catch (error) {
      console.error(error)
      toast.error('Failed to export questions')
    }
  }
  exportQuestions()
}
</script>

<style scoped></style>
