<?php

namespace App\Repositories;

use App\Eloquent\BookUser;
use App\Contracts\Repositories\BookUserRepository;
use App\Eloquent\Notification;
use App\Events\NotificationHandler;
use Illuminate\Support\Facades\Event;
use App\Exceptions\Api\ActionException;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknownException;
use Carbon\Carbon;

class BookUserRepositoryEloquent extends AbstractRepositoryEloquent implements BookUserRepository
{
    public function model()
    {
        return new BookUser;
    }

    public function getData($data = [], $with = [], $dataSelect = ['*'])
    {
        $date = date_modify(Carbon::now(), config('model.prompt.time'));
        $book_users = $this->model()
            ->select($dataSelect)
            ->with($with)
            ->where('updated_at', '<=', $date)
            ->where($data)
            ->get()
            ->groupBy('owner_id');

        return $book_users;
    }

    public function createPrompt($send_id, $receive_id, $type, $book_title = '', $book_id = 0)
    {
        Event::fire('androidNotification', $type);
        $message = sprintf(translate('notification.' . $type), $book_title);
        event(new NotificationHandler($message, $receive_id, $type));
        Event::fire('notification', [
            [
            'current_user_id' => $send_id,
            'get_user_id' => $receive_id,
            'target_id' => $book_id,
            'type' => $type,
            ]
        ]);
    }
}
