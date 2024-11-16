<?php

namespace App\Http\Controllers;

class ApiCompositionController
{
    public function __construct(
        private $microservice1Client,
        private $microservice2Client,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        // Call to microservice 1
        $responseDTO1 = $this->microservice1Client->get();

        // Call to microservice 2
        $responseDTO2 = $this->microservice2Client->get();

        // Merge results into single response
        return response(
            [
                'field1' => $responseDTO1['dataFromFirstMicorservice'],
                'field2' => $responseDTO2['dataFromSecondMicroservice'],
            ]
        );
    }
}
