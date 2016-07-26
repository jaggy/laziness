<?php

namespace Work\Messaging;

use Work\Network\Network;

class Skype
{
    /**
     * Run the applescript to send a message to the skype client.
     *
     * @param  string  $message
     * @return void
     */
    public function send($message)
    {
        $script = __DIR__ . '/MacOS/send_message.js';

        exec("osascript -l JavaScript {$script} '{$message}'");
    }
}
