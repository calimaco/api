<?php

namespace App\Traits;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

trait HttpResponses
{

    public function response(string $message, int $status, array $data = [], string $warning = null)
    {
        $res = [];
        $res['message'] = $message;
        $res['status'] = $status;
        isset($warning) ? $res['warning'] = $warning : null;
        $res['data'] = $data;

        return response()->json($res, $status);
    }

    public function error(string $message, int $status, string $error = null, array $data = [])
    {
        return response()->json([
        'message' => $message,
        'status' => $status,
        'error' => $error,
        'data' => $data
        ], $status);
    }

    public function recordsResponse(
        string $message,
        int $status,
        int $totalRecords,
        int $page,
        int $limit,
        object $records,
        ?string $warning
    )
    {
        $res = [];
        $res['message'] = $message;
        $res['status'] = $status;
        isset($warning) ? $res['warning'] = $warning : null;
        $rest['totalRecords'] = $totalRecords;
        $rest['page'] = $page;
        $rest['limit'] = $limit;
        $rest['data'] = $records;
        
        return response()->json($res, $status);
    }
}