<?php

namespace App\Console\Commands;

use App\Models\MessageReceiver;
use App\Models\Movie;
use Illuminate\Console\Command;

class FulfillMissingMessageReceivers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fulfill:message_receivers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fulfill the missing message receivers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $movies = Movie::notEnd()->get();

        foreach ($movies as $movie) {
            $this->info('processing movie:' . $movie->FID);
            $unDeletedMessages = $movie->messages()->where([
                'messages.is_delete' => 0,
                'messages.is_undo'   => 0
            ])->get();

            if ($unDeletedMessages->count() == 0) {
                $this->warn('movie' . $movie->FID . ' has no messages');
                continue;
            }

            foreach ($unDeletedMessages as $message) {
                $this->info('processing message' . $message->id);
                $scopeIds = explode(',', $message->scope_ids);

                $receivers = $message->receivers->pluck('receiver_id')->all();

                $missedUserIds = array_diff($scopeIds, $receivers);

                if (count($missedUserIds) == 0) {
                    $this->warn('message' . $message->id . ' has no missing receivers');
                    continue;
                }

                foreach ($missedUserIds as $missedUserId) {
                    $this->info('creating receiver:' . $message->id . '-' . $missedUserId);
                    $messageReceiver              = new MessageReceiver;
                    $messageReceiver->message_id  = $message->id;
                    $messageReceiver->receiver_id = $missedUserId;
                    $messageReceiver->is_read     = 0;
                    $messageReceiver->created_at  = $message->created_at;
                    $messageReceiver->updated_at  = $message->updated_at;
                    $messageReceiver->save();
                }
            }
        }
    }
}
