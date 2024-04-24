import type { Answer } from '@/models/Answer'
import type { Question } from '@/models/Question'
import type { VoteResult } from '@/models/VoteResult'
import type { VoteClosure } from '@/models/VoteClosure'
import { api } from '@/utils/api'

class VoteService {
  async getQuestionByCode(code: string): Promise<Question> {
    return await api(`/vote/${code}`, {
      method: 'GET'
    })
  }

  async vote(answer: Answer, code: string): Promise<void> {
    return await api(`/vote/${code}/answer`, {
      method: 'POST',
      body: answer
    })
  }

  async getLatestResults(code: string): Promise<VoteResult> {
    return await api(`/vote/${code}/results`, {
      method: 'GET'
    })
  }

  async getQuestionClosures(code: string): Promise<VoteClosure[]> {
    return await api(`/vote/${code}/closures`, {
      method: 'GET'
    })
  }

  async closeVote(code: string, note: string): Promise<void> {
    return await api(`/vote/${code}/close`, {
      method: 'POST',
      body: { note }
    })
  }

  async getArchivedResults(code: string, closure_id: string): Promise<VoteResult> {
    return await api(`/vote/${code}/results-archive/${closure_id}`, {
      method: 'GET'
    })
  }
}

export default new VoteService()
