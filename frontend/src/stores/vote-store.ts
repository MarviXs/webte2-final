import type { Answer } from '@/models/Answer'
import type { Question } from '@/models/Question'
import VoteService from '@/services/VoteService'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useVoteStore = defineStore('voteStore', () => {
  const question = ref<Question | null>(null)
  const questionLoading = ref(false)

  async function getQuestionByCode(code: string) {
    questionLoading.value = true
    question.value = null

    try {
      question.value = await VoteService.getQuestionByCode(code)
    } catch (error) {
      throw new Error('Code not found!')
    } finally {
      questionLoading.value = false
    }
  }

  async function submitAnswer(answer: Answer) {
    try {
      await VoteService.vote(answer, question.value!.code)
    } catch (error) {
      throw new Error('Failed to submit answer!')
    }
  }

  return {
    question,
    questionLoading,
    getQuestionByCode,
    submitAnswer
  }
})
