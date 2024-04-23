import type { Choice } from './Choice'

enum QuestionType {
  MULTIPLE_CHOICE = 'multiple_choice',
  SINGLE_CHOICE = 'single_choice',
  OPEN = 'open'
}

interface Question {
  id: string
  question_text: string
  question_type: QuestionType
  code: string
  is_active: boolean
  subject?: string
  choices: Choice[]
  created_at: number
  updated_at: number
}

interface QuestionRequest {
  id?: string
  question_text: string
  question_type: QuestionType
  choices: Choice[]
  is_active: boolean
  subject?: string
}

export type { Question, QuestionRequest }
export { QuestionType }
