<?php

namespace App\Http\Controllers;

use App\Models\LogError;
use Illuminate\Http\Request;


class ApiBaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message = 'success')
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data'    => $result,
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 500)
    {
        $attributes = request()->all();
        $route = request()->path();
        $response = [
            'status' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['error'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Pagination Mapping
     * @param $data
     * @param $query
     * @param bool $source
     * @return void
     */
    public function paginationMap(&$data, $query, $request, $id = 'id')
    {
        if ($request->source)
            $data = $query->get();
        else {
            $orderDirection = $request->order_direction ?? 'ASC';
            $orderName = $request->order_column ?? $id;

            $data = $query->orderByRaw($orderName . " " . $orderDirection)
                ->paginate(
                    $request->perPage ?? 10,
                    ['*'],
                    'page',
                    $request->page ?? 1
                );
        }
    }
}
