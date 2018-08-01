<?php

namespace App\Console\Commands;

use App\Contracts\Repositories\BookUserRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Prompt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prompt:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get notifications to prompt user return book';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $repository;
    public function __construct(BookUserRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = [
            'status' => config('model.book_user.status.reading')
        ];
        $book_users = $this->repository->getData($data);
        $book_users->each(function($items, $key) {
            $this->repository->createPrompt($key, $key, config('model.notification.owner_prompt'));
            foreach ($items as $item) {
                $this->repository->createPrompt($item->owner_id, $item->user_id, config('model.notification.user_prompt'), $item->book_id);
            }
        });
    }
}
