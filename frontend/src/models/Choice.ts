interface Choice {
  id: string
  choice_text: string
}

interface ChoiceRequest {
  choice_text: string
}

export type { Choice, ChoiceRequest }
