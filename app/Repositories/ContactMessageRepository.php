<?php

namespace App\Repositories;

use App\Models\ContactMessage;
use Prettus\Repository\Eloquent\BaseRepository;

class ContactMessageRepository extends BaseRepository
{

    public function model()
    {
        return ContactMessage::class;
    }
    public function getAll()
    {
        return $this->model->all();
    }

    public function paginated($limit = 10, $oderBy = "created_at", $sort = "desc")
    {
        return $this->model
            ->where(function ($query) {
                $query->when(request()->search, function ($q, $searchKey) {
                    $q->where(function ($query) use ($searchKey) {
                        return $query->where('name', 'like', '%' . $searchKey . '%')
                            ->orWhere('email', 'like', '%' . $searchKey . '%')
                            ->orWhere('phone', 'like', '%' . $searchKey . '%');
                    });
                });
            })
            ->orderBy('created_at', $sort)
            ->paginate($limit);
    }

    public function getOneById(int $id)
    {
        $message = $this->model->find($id);
        if (!$message) {
            return null;
        }
        if ($message->seen == ContactMessage::STATUS_UNSEEN) {
            $message->seen = ContactMessage::STATUS_SEEN;
            $message->save();
        }
        return $message;
    }

}
