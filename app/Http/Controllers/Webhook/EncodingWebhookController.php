<?php

namespace App\Http\Controllers\Webhook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Media\Video;
use Log;

class EncodingWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info($request);
        
    	$event = camel_case($request->event);


    	if(method_exists($this, $event)) {
    		$this->{$event}($request);
    	}
    }

    protected function videoEncoded(Request $request)
    {
    	$video = $this->getVideoByFilename($request->original_filename);

    	$video->processed = true;
    	$video->video_id = $request->encoding_ids[0];

    	$video->save();
    }

    protected function encodingProgress(Request $request)
    {
    	$video = $this->getVideoByFilename($request->original_filename);

    	$video->processed_percentage = $request->progress;

    	$video->save();

    }

    protected function getVideoByFilename($filename)
    {
    	return Video::where('video_filename', $filename)->firstOrFail();
    }	
}
