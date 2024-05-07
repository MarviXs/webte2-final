<template>
  <div class="container-dashboard q-mb-lg">
    <q-tabs
      class="text-grey"
      active-color="primary"
      inline-label
      indicator-color="primary"
      align="justify"
      v-model="tab"
    >
      <q-tab name="list" label="List" no-caps :icon="mdiFormatListBulleted" />
      <q-tab
        v-if="voteResult.question_type === QuestionType.SINGLE_CHOICE"
        name="pie_chart"
        label="Graph"
        no-caps
        :icon="mdiChartPie"
      />
      <q-tab
        v-if="voteResult.question_type === QuestionType.MULTIPLE_CHOICE"
        name="chart_bar"
        label="Graph"
        no-caps
        :icon="mdiChartBar"
      />
      <q-tab
        v-if="voteResult.question_type === QuestionType.OPEN"
        name="cloud"
        label="Word Cloud"
        no-caps
        :icon="mdiCloud"
      />
    </q-tabs>
    <q-tab-panels v-model="tab" animated class="border-top">
      <q-tab-panel name="list" class="q-pa-none">
        <q-table
          :rows="voteResult.answers"
          flat
          :columns="columns"
          row-key="answer"
          :rows-per-page-options="[5, 10, 20]"
          no-data-label="No results found"
          rows-per-page-label="Answers per page"
          loading-label="Loading results..."
        >
        </q-table>
      </q-tab-panel>

      <q-tab-panel name="pie_chart">
        <div v-if="totalVotes > 0" class="row justify-center">
          <apexchart
            type="pie"
            width="100%"
            class="full-width"
            height="350"
            :options="pieChartOptions"
            :series="pieSeries"
          ></apexchart>
        </div>
        <div v-else>
          <div class="text-center">No votes yet</div>
        </div>
      </q-tab-panel>

      <q-tab-panel name="chart_bar">
        <div v-if="totalVotes > 0" class="row justify-center">
          <apexchart
            type="bar"
            class="full-width"
            width="100%"
            height="400"
            :options="barChartOptions"
            :series="barSeries"
          ></apexchart>
        </div>
        <div v-else>
          <div class="text-center">No votes yet</div>
        </div>
      </q-tab-panel>

      <q-tab-panel name="cloud">
        <div v-if="totalVotes > 0">
          <div class="wordCloud">
            <div class="word" v-for="result in voteResult.answers" :key="result.answer" 
            :style="{
            position: 'absolute',
            top: `${Math.floor(Math.random() * 75)}%`,
            left: `${Math.floor(Math.random() * 75)}%`
            }"
            >
                <span :style="{ 
                fontSize: `${(result.count < 16) ? 16 + (result.count*5): 96}px`,
                color: (result.count > 10) ? '#5edde4' : (result.count > 6) ? '#f04040' : (result.count > 3) ? '#fb9035' : '#2fc92f'
                }">{{ result.answer }}
                </span>
                <q-tooltip content-style="font-size: 11px" :offset="[0, 4]">{{ result.count }}</q-tooltip>
            </div>
          </div>
        </div>
        <div v-else>
          <div class="text-center">No votes yet</div>
        </div>
      </q-tab-panel>
    </q-tab-panels>
  </div>
</template>

<script setup lang="ts">
import type { VoteResult } from '@/models/VoteResult'
import { mdiChartBar, mdiChartPie, mdiCloud, mdiFormatListBulleted } from '@quasar/extras/mdi-v7'
import { ref, type PropType } from 'vue'
import type { QTableProps } from 'quasar'
import { QuestionType } from '@/models/Question'
import { computed, watchEffect, nextTick } from 'vue'

const props = defineProps({
  voteResult: {
    type: Object as PropType<VoteResult>,
    required: true
  }
})

const totalVotes = computed(() =>
  props.voteResult.answers.reduce((acc, answer) => acc + answer.count, 0)
)

const tab = ref<string>('list')
if (props.voteResult.question_type === QuestionType.SINGLE_CHOICE) {
  tab.value = 'pie_chart'
} else if (props.voteResult.question_type === QuestionType.MULTIPLE_CHOICE) {
  tab.value = 'chart_bar'
}

const pieSeries = computed(() => props.voteResult.answers.map((answer) => answer.count))
const pieChartOptions = ref({
  labels: props.voteResult.answers.map((answer) => answer.answer),
  legend: {
    position: 'bottom'
  }
})

const barSeries = computed(() => [
  {
    name: 'Votes',
    data: props.voteResult.answers.map((answer) => answer.count)
  }
])
const barChartOptions = ref({
  labels: props.voteResult.answers.map((answer) => answer.answer),
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
    }
  },
  legend: {
    position: 'bottom'
  }
})

const columns: QTableProps['columns'] = [
  {
    name: 'answer',
    label: 'Answer',
    field: 'answer',
    align: 'left',
    sortable: true
  },
  {
    name: 'count',
    label: 'Count',
    field: 'count',
    align: 'left',
    sortable: true
  }
]

watchEffect(()=>{
  if(tab.value === 'cloud'){
    nextTick(()=>{
      const words = document.querySelectorAll('.word')
      const firstWord = words[0] as HTMLElement
      firstWord.style.top = '30%'
      firstWord.style.left = '50%'
      let spacing = 15
      //position the rest of the words tightly around the first word to avoid overlap but also to make it look like a cloud
      for(let i = 1; i < words.length; i++){
        spacing = 15
        console.log(i, words.length)
        if(i === 1){
          let direction = "up"
          for(let j = 1; j < Math.min(words.length, 5); j++){
            const word = words[j] as HTMLElement
            const spanChild = word.querySelector('span') as HTMLElement
            if(spanChild.style.color === "#2fc92f") spacing = 5
            else if(spanChild.style.color === "#fb9035") spacing = 7
            else if(spanChild.style.color === "#f04040") spacing = 10
            else if(spanChild.style.color === "#5edde4") spacing = 15

            if(direction === "up"){
              word.style.top = `${parseInt(firstWord.style.top) - spacing}%`
              word.style.left = `${parseInt(firstWord.style.left)}%`
              direction = "right"
            } else if(direction === "right"){
              word.style.top = `${parseInt(firstWord.style.top)}%`
              word.style.left = `${parseInt(firstWord.style.left) + spacing}%`
              direction = "left"
            } else if(direction === "left"){
              word.style.top = `${parseInt(firstWord.style.top)}%`
              word.style.left = `${parseInt(firstWord.style.left) - spacing}%`
              direction = "down"
            } else if(direction === "down"){
              word.style.top = `${parseInt(firstWord.style.top) + spacing}%`
              word.style.left = `${parseInt(firstWord.style.left)}%`
            }
            i=j
          }
        } else {
          let direction = "right"
          const centerWord = words[i-1] as HTMLElement
          for(let j = i; j < Math.min(words.length, i+5); j++){
            const word = words[j] as HTMLElement
            const spanChild = word.querySelector('span') as HTMLElement
            if(spanChild.style.color === "#2fc92f") spacing = 5
            else if(spanChild.style.color === "#fb9035") spacing = 7
            else if(spanChild.style.color === "#f04040") spacing = 10
            else if(spanChild.style.color === "#5edde4") spacing = 15

            if(direction === "right"){
              word.style.top = `${parseInt(centerWord.style.top)}%`
              word.style.left = `${parseInt(centerWord.style.left) + spacing}%`
              direction = "left"
            }  else if(direction === "left"){
              word.style.top = `${parseInt(centerWord.style.top)}%`
              word.style.left = `${parseInt(centerWord.style.left) - spacing}%`
              direction = "down"
            } else if(direction === "down"){
              word.style.top = `${parseInt(centerWord.style.top) + spacing}%`
              word.style.left = `${parseInt(centerWord.style.left)}%`
            }
            i=j
          }
          console.log(i)
        }
      }
      //check if the last word is not positioned outside the container and if it is extend the container
      const lastWord = words[words.length-1] as HTMLElement
      const container = document.querySelector('.wordCloud') as HTMLElement
      if(parseInt(lastWord.style.top) > 100){
        container.style.minHeight = `${parseInt(container.style.minHeight) + 100}px`
      }
    })
  }
})

</script>

<style lang="scss" scoped>
.border-top {
  border-top: 1px solid #e0e0e0;
}
.wordCloud {
  min-width: 640px;
  min-height: 480px;
}
</style>
