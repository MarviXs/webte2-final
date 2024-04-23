import type { Choice, ChoiceRequest } from '@/models/Choice'
import { api } from '@/utils/api'

class ChoiceService {
  async createChoice(choice: ChoiceRequest, question_id: string): Promise<Choice> {
    return await api(`/questions/${question_id}/choices`, {
      method: 'POST',
      body: choice
    })
  }

  async deleteChoice(choice_id: string): Promise<void> {
    return await api(`/choices/${choice_id}`, {
      method: 'DELETE'
    })
  }
}

export default new ChoiceService()
