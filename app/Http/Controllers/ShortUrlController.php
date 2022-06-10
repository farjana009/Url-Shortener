<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ShortUrl;

class ShortUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shortUrls = ShortUrl::latest()->get();
//        dd($shortUrls);
//        die();
        if ($shortUrls)
            return response()->json(['success' => true, 'message' => '', 'data' => $shortUrls]);
        else
            return response()->json(['success' => false, 'message' => '', 'data' => null]);

//        return view('shortenUrl', compact('shortUrls'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function linkShortStore(Request $request)
    {
//        echo 1;die();

        $request->validate([
            'link' => 'required|url'
        ]);
        $letter = array_merge(range('a', 'z'), range('A', 'Z'));
        $random_int = rand(0, 9);
        $hash = $letter[rand(0, 51)] . $random_int . Str::random(4);
        $csrf = $request->csrf;
//        dd($hash);

        $new_link = $request->link;
        $checkIfExistLink = ShortUrl::where('link', $new_link)->first();

        if ($checkIfExistLink) {
//            echo $checkIfExistLink->link;
            return response()->json(['success' => true, 'message' => 'Shorten Url Already Exist', 'data' => $checkIfExistLink]);
        } else {
            $input['link'] = $new_link;
            $input['hash'] = $hash;

            $created = ShortUrl::create($input);
            if ($created) {
                return response()->json(['success' => true, 'message' => 'Shorten Url Generated Successfully!', 'data' => $created]);
//            return redirect('generate-short-url')
//                ->with('success', 'Shorten Url Generated Successfully!');

            }
            return response()->json(['success' => false, 'message' => 'Shorten Url Failed!', 'data' => null]);

        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shortenUrl($hash)
    {
        $find = ShortUrl::where('hash', $hash)->first();
        //dd($find);
//        echo 1;die();
        if ($find) {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=AIzaSyAtnx-E3yUAEh92X4QUiY-DpINJnLAuxhc",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => '  {
"client": {
  "clientId":      "safe-browsing-api",
  "clientVersion": "1.5.2"
},
"threatInfo": {
  "threatTypes":      ["MALWARE", "SOCIAL_ENGINEERING"],
  "platformTypes":    ["WINDOWS"],
  "threatEntryTypes": ["URL"],
  "threatEntries": [
    {"url": "' . $find->link .

                    '" }


  ]
}
  }',
                CURLOPT_HTTPHEADER => array( // malware url http://malware.testing.google.test/testing/malware/
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: b05b8d34-85f2-49cf-0f8e-03686a71e4e9"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $obj_response = json_decode($response);
//            print_r($obj_response->matches[0]->threatType) ;die();

            curl_close($curl);

            if ($err) {// error found
//                    echo "cURL Error #:" . $err;
                $message = "cURL Error #:" . $err;
                return view('message', compact('message'));
            } else {
                if (!(array)$obj_response) { // no threats found
                    return redirect()->to($find->link);
//                    echo $response;

                } else { // threats found      (array)$obj_response

//                echo "<script>setTimeout(function(){alert('MALWARE FOUND')}, 3000);</script>";
//                return redirect()->back();
                    $message = 'MALWARE FOUND';
                    return view('message', compact('message'));
                }
            }

        } else {
            return redirect()->back();

        }

    }
}
