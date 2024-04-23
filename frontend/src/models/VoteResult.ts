interface AnswerCount {
  answer: string
  count: number
}

interface VoteResult {
  question_type: string
  answers: AnswerCount[]
}

export type { AnswerCount, VoteResult }
