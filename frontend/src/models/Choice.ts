interface Choice {
  id: string
  choice_text: string
  order: number
}

interface ChoiceRequest {
  choice_text: string
  order: number
}

export type { Choice, ChoiceRequest }
