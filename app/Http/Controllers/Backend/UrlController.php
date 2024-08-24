<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UrlSubmissionRequest;
use App\Jobs\ProcessUrls;
use App\Models\Url;
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

        return redirect()->route('url.list')->with('success', $message);
    }

    public function list(Request $request){
        $query = Url::query();

        // Search functionality
        if ($request->has('search')) {
            $query->where('full_url', 'like', '%' . $request->search . '%')
                ->orWhere('created_at', 'like', '%' . $request->search . '%');
        }

        // Sorting functionality
        if ($request->has('sort_by') && $request->has('sort_dir')) {
            $query->orderBy($request->sort_by, $request->sort_dir);
        } else {
            $query->orderBy('created_at', 'desc'); // Default sorting
        }

        // Pagination
        $urls = $query->paginate($request->get('limit', 10));

        // Ajax request
        if($request->ajax()){
            return response()->json($urls);
        }

        return view('pages.url.list', compact('urls'));

    }
}
