import type { Choice, ChoiceRequest } from '@/models/Choice'
import { api } from '@/utils/api'

class ChoiceService {
  async createChoice(choice: ChoiceRequest, question_id: string): Promise<Choice> {
    return await api(`/questions/${question_id}/choices`, {
      method: 'POST',
      body: choice
    })
  }
}

export default new ChoiceService()
