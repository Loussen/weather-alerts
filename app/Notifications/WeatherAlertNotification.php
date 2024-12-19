<?php

namespace App\Notifications;

use App\Models\City;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class WeatherAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected City $city,
        protected string $type,
        protected string $message
    ) {}

    public function via($notifiable)
    {
        // Email ve Slack kanallarını kullanıyoruz (ikinci kanal olarak Slack'i seçtim)
        return ['mail', 'slack'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Weather Alert for {$this->city->name}")
            ->greeting("Hello {$notifiable->name}!")
            ->line($this->message)
            ->action('View Alert Details', url('/weather/alerts'))
            ->line('Stay safe!');
    }

    public function toSlack($notifiable)
    {
        $emoji = $this->type === 'uv' ? '☀️' : '🌧️';

        return (new SlackMessage)
            ->warning()
            ->from('Weather Alert System')
            ->to('#weather-alerts')
            ->content("{$emoji} {$this->message}");
    }

    public function toArray($notifiable)
    {
        return [
            'city_id' => $this->city->id,
            'type' => $this->type,
            'message' => $this->message
        ];
    }
}
