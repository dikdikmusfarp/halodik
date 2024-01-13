<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends ApiBaseController
{
    public function list(Request $request)
    {
        $attributes = $request->all();
        try {
            $data = User::select(
                'id',
                'email',
                'name',
                'updated_at',
                'is_online'
            )
            ->where('id', '!=', Auth::user()->id)
            ->where(function ($query) use ($attributes) {
                if (isset($attributes['search'])) {
                    $query->where('name', 'ilike', '%' . $attributes['search'] . '%')
                        ->orWhere('email', 'ilike', '%' . $attributes['search'] . '%');
                }
            })
            ->get();
            if ($data) {
                foreach ($data as $key => $value) {
                    $chat = Chat::select(
                        'chats.id',
                        'chats.isi',
                        'chats.penerima as id_penerima',
                        'penerima.name as nama_penerima',
                        'chats.pengirim as id_pengirim',
                        'pengirim.name as nama_pengirim',
                        'chats.created_at as waktu_pengiriman',
                        'chats.read_status'
                        )
                    ->leftJoin('users as penerima', 'penerima.id', '=', 'chats.penerima')
                    ->leftJoin('users as pengirim', 'pengirim.id', '=', 'chats.pengirim')
                    ->where(function ($query) use ($value) {
                        $query->where('chats.pengirim', Auth::user()->id)
                              ->where('chats.penerima', $value->id);
                    })
                    ->orWhere(function ($query) use ($value) {
                        $query->where('chats.penerima', Auth::user()->id)
                              ->where('chats.pengirim', $value->id);
                    })
                    ->orderBy('chats.created_at', 'desc')
                    ->first();
                    $value['chat'] = $chat;
                }
                return $this->sendResponse($data, 'Menampilkan list user');
            } else {
                throw new \Exception("Error, undefined type");
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
