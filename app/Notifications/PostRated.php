<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostRated extends Notification
{
    use Queueable;

    public $rater;
    public $post;
    public $rating;

    /**
     * Create a new notification instance.
     */
    public function __construct($rater, $post, $rating)
    {
        $this->rater = $rater;
        $this->post = $post;
        $this->rating = $rating;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your post was rated!')
            ->line($this->rater->name . ' rated your post: ' . $this->post->title)
            ->action('View Post', url('/posts/' . $this->post->id . '/view'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->rater->name} rated your post '{$this->post->title}' with {$this->rating} stars.",
            'post_id' => $this->post->id,
            'rating' => $this->rating
        ];
    }
}
