'use client'

type Props = {
    mediaType: string
    mediaUrl: string
}

export default function MediaItem({ mediaType, mediaUrl }: Props) {
  if (mediaType === 'image' && mediaUrl) {
    return (
      <div className="w-full my-4">
        <img src={mediaUrl} alt="Question Media" className="max-w-full h-auto rounded" />
      </div>
    )
  }
  if (mediaType === 'video' && mediaUrl) {
    return (
        <div className="w-full my-4 aspect-video">
            <iframe
            width="100%"
            height="100%"
            src={`https://www.youtube.com/embed/${new URLSearchParams(new URL(mediaUrl).search).get('v')}`}
            title="YouTube video"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowFullScreen
            ></iframe>
        </div>
        )
    }
    return <div></div>
}
