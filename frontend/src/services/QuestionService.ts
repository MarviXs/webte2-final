import type { Question, QuestionRequest } from '@/models/Question'
import { api } from '@/utils/api'

class QuestionService {
  async getQuestions(): Promise<Question[]> {
    return await api('/questions', {
      method: 'GET',
    })
  }

  async getQuestion(id: string): Promise<Question> {
    return await api(`/questions/${id}`, {
      method: 'GET',
    })
  }

  async createQuestion(question: QuestionRequest): Promise<Question> {
    return await api('/questions', {
      method: 'POST',
      body: question,
    })
  }

  async deleteQuestion(id: string): Promise<void> {
    await api(`/questions/${id}`, {
      method: 'DELETE',
    })
  }
}

export default new QuestionService()