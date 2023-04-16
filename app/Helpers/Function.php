<?php 
    function SuccessResponse($data){
        $response = [
            'success' => true,
            'error' => null,
            'body' => $data
        ];

        return $response;
    }
    function ErrorResponse($error){
        $response = [
            'success' => false,
            'error' => $error,
            'body' => null
        ];

        return $response;
    }