<?php

function jsonResponse($message, $code = 400, $status = 'error')
{
    return response()->json([
        'status' => $status,
        'code' => $code,
        'message' => $message
    ],$code);
}

function customResponse($message, $code = 400, $status = 'error')
{
    return response()->json([
        'status' => $status,
        'code' => $code,
        'message' => $message
    ],400);
}