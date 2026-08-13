export interface Participant {
  id: number
  name: string
  role: 'client' | 'manager' | 'admin'
}

export interface DialogueListItem {
  id: number
  outcome: 'purchased' | 'not_purchased'
  manager: Participant
  client: Participant
  messages_count: number
  created_at: string
}

export interface Message {
  id: number
  text: string
  sent_at: string
  sender: Participant
}

export interface Pagination {
  current_page: number
  last_page: number
  per_page: number
  total: number
  has_more: boolean
}

export interface Paginated<T> {
  data: T[]
  pagination: Pagination
}
