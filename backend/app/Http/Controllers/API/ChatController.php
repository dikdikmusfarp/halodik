<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends ApiBaseController
{
    // MEMBUAT DATA BARU PADA TABEL CHAT
    public function store(Request $request)
    {
        try {
            $request->validate([
                'isi' => 'required|string',
                'penerima' => 'required|numeric',
            ]);
            if ($request->isi) {
                DB::beginTransaction();
                $model = new Chat();
                $model->isi = $request->isi; // Membuat huruf awal pada kata menjadi kapital
                $model->penerima = $request->penerima;
                $model->pengirim = Auth::user()->id;
                $model->save();
                DB::commit();
                return $this->sendResponse($model, 'chat berhasil dibuat');
            } else {
                throw new \Exception("Pastikan isian sudah lengkap");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function chatRoom($id)
    {
        try {
            $chat = Chat::select('*')->where('penerima', Auth::user()->id)->where('pengirim', $id)->where('read_status', false)->get();
            $totalChat = count($chat);
            if ($totalChat > 0) {
                DB::beginTransaction();
                foreach ($chat as $key => $value) {
                    $value->read_status = true;
                    $value->save();
                }
                DB::commit();
            }
            $data = Chat::select(
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
            ->where(function ($query) use ($id) {
                $query->where('chats.pengirim', Auth::user()->id)
                      ->where('chats.penerima', $id);
            })
            ->orWhere(function ($query) use ($id) {
                $query->where('chats.penerima', Auth::user()->id)
                      ->where('chats.pengirim', $id);
            })
            ->orderBy('chats.created_at')
            ->get();
            if ($data) {
                return $this->sendResponse($data, 'Menampilkan list di chat room spesifik');
            } else {
                throw new \Exception("Error, undefined type");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    // MENGUBAH STATUS PESAN
    public function read($id)
    {
        try {
            $chat = Chat::select('*')->where('penerima', Auth::user()->id)->where('pengirim', $id)->where('read_status', false)->get();
            $totalChat = count($chat);
            if ($totalChat > 0) {
                DB::beginTransaction();
                foreach ($chat as $key => $value) {
                    $value->read_status = true;
                    $value->save();
                }
                DB::commit();
                return $this->sendResponse($chat, 'chat berhasil dibaca');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

}
