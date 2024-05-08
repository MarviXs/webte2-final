
interface AnswerCount {
  answer: string
  count: number
}

interface VoteResult {
  question_type: string
  answers: AnswerCount[]
}

interface VoteClosure {
  id: string
  note: string
  created_at: string
}
interface VoteClosureResult {
  closure: VoteClosure
  question_type: string
  answers: AnswerCount[]
}

export type { AnswerCount, VoteResult, VoteClosureResult, VoteClosure}
