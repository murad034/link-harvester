<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UrlSubmissionRequest;
use App\Jobs\ProcessUrls;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function create(){
        return view('pages.url.form');
    }

    public function store(UrlSubmissionRequest $request) {
        $data = $request->all();
        $urls = explode("\n", str_replace("\r", "", trim($data['urls'])));

        // Dispatch the job to process the URLs in the background
        $result = ProcessUrls::dispatch($urls);

        // Prepare a response message
        $message = "URLs processed successfully";
        /*
        $insertedCount = $result["insertedCount"];
        $duplicateCount = $result["duplicateCount"];

        if($insertedCount <= 0){
            $message = "Unsuccessful, All URL are duplicate!!";
            return redirect()->route('url.create')->with('error', $message)->withInput();
        }
        $message = "$insertedCount URLs inserted successfully.";
        if ($duplicateCount > 0) {
            $message .= " $duplicateCount duplicate URLs were skipped.";
        }
        */

        return redirect()->route('url.create')->with('success', $message);
    }

}
