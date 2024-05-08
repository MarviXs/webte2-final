<template>
  <PageLayout
    title="Vote Archive"
    previous-title="Results"
    :previous-route="`/questions/${route.params.code}/results`"
  >
    <template #default>
      <div class="shadow">
        <q-table
          :rows="closures"
          :columns="columns"
          row-key="id"
          flat
          :loading="loadingClosures"
          :rows-per-page-options="[10, 20, 30]"
          no-data-label="No vote closures found"
          loading-label="Loading vote archive..."
          rows-per-page-label="Vote closures per page"
        >
          <template #body-cell-actions="propsActions">
            <q-td auto-width :props="propsActions">
              <q-btn
                :icon="mdiChartBar"
                color="gray-btn"
                flat
                round
                :to="`/${route.params.code.toString()}/archive/${propsActions.row.id}`"
              >
                <q-tooltip content-style="font-size: 11px" :offset="[0, 4]"> Results </q-tooltip>
              </q-btn>
            </q-td>
          </template>
        </q-table>
        <div class="closure-comparisons" v-if="notOpen && !noData">
          <apexchart
            ref="barChart"
            type="bar"
            class="full-width"
            width="100%"
            height="400"
            :options="barChartOptions"
            :series="barSeries"
          ></apexchart>
        </div>
      </div>
    </template>
  </PageLayout>
</template>

<script setup lang="ts">
import PageLayout from '@/layouts/PageLayout.vue'
import { ref, computed } from 'vue'
import { toast } from 'vue3-toastify'
import type { QTableProps } from 'quasar'
import type { VoteClosure } from '@/models/VoteClosure'
import type { VoteClosureResult, VoteResult } from '@/models/VoteResult'
import VoteService from '@/services/VoteService'
import { QuestionType } from '@/models/Question'
import { useRoute } from 'vue-router'
import { mdiChartBar } from '@quasar/extras/mdi-v7'
import { useI18n } from 'vue-i18n'

const { t, locale } = useI18n()

const route = useRoute()
const closures = ref<VoteClosure[]>([])

const loadingClosures = ref(false)

const VoteResults = ref<VoteClosureResult[]>([])
const categories = ref<string[]>(["",""])
const barChart = ref<any>(null)
let notOpen = ref(true)
let noData = ref(false)
const CurrentVoteResult = ref<VoteResult>()

async function getClosures() {
  const questionCode = route.params.code as string
  try {
    loadingClosures.value = true
    closures.value = await VoteService.getQuestionClosures(questionCode)
    //Get current vote result
    CurrentVoteResult.value = await VoteService.getLatestResults(questionCode)
    if (CurrentVoteResult.value.question_type === QuestionType.OPEN) {
      notOpen.value = false
    } else if(closures.value.length === 0){
      noData.value = true
    } 
    else {
      //Get all closed vote results
      for (const closure of closures.value) {
        const results = await getVoteResults(closure.id)
        const answers = results.answers
        const question_type = results.question_type
        VoteResults.value.push({
          closure,
          answers,
          question_type
        })
      }
      categories.value = VoteResults.value[0].answers.map((answer) => answer.answer)
      barChartOptions.value.labels = categories.value
      barChart.value.updateOptions(barChartOptions.value)
    }
  } catch (error) {
    console.error(error)
    toast.error('Failed to load question closures')
  } finally {
    loadingClosures.value = false
  }
}
getClosures()

function getVoteResults(closureId: string) {
  return VoteService.getArchivedResults(route.params.code.toString(), closureId)
}


const columns: QTableProps['columns'] = [
  {
    name: 'closed_at',
    label: 'Closed at',
    field: 'created_at',
    align: 'left',
    sortable: true,
    format(val) {
      return new Date(val * 1000).toLocaleString(locale.value)
    }
  },
  {
    name: 'note',
    label: 'Note',
    field: 'note',
    align: 'left',
    sortable: true
  },
  {
    name: 'actions',
    label: 'Actions',
    align: 'center',
    field: ''
  }
]

const barChartOptions = ref({
  labels: categories.value,
  yaxis: {
    title: {
      text: 'Votes',
      style: {
        fontSize: '14px',
        fontFamily: 'Inter',
        fontWeight: 'bold'
      }
    }
  }, 
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '50%',
      endingShape: 'rounded'
    },
    stacked: true
  },
  legend: {
    position: 'bottom',
  }
})
const barSeries = computed(() => {
  return VoteResults.value.map((result) => {
    return {
      name: new Date(parseInt(result.closure.created_at) * 1000).toLocaleString(locale.value),
      data: result.answers.map((answer) => {return{
        x: answer.answer,
        y:  answer.count,
        goals: [{
          name: 'Current Vote',
          value: CurrentVoteResult.value?.answers.find((a) => a.answer === answer.answer)?.count,
          strokeHeight: 5,
          strokeColor: '#ed008c',
        }]
      }}),
    }
  })
})
</script>

<style scoped></style>
