export type Question = {
  id?: number | string
  questionText?: string
  mediaType?: 'image' | 'video' | 'text'
  mediaUrl?: string
  choices?: string[]
}
