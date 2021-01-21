<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ravenEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $link;

    public function __construct($name, $link)
    {
        $this->name = $name;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Fulltimeforce - Prueba Psicologica')
                    ->markdown('emails.raven')
                    ->with([
                        'name'=>$this->name,
                        'link'=>$this->link,
                        'banner'=>asset('mail/mail_banner.png'),
                        'footer'=>asset('mail/mail_footer.png'),
                        'img'=>asset('mail/mail_img.png'),
                        'logo'=>asset('mail/mail_logo.png'),
                    ]);
                    // ->with('name', $this->name)
                    // ->with('link', $this->link);
        // return $this->markdown('emails.raven');
    }
}
