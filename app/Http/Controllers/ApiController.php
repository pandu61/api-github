<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index() {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/user/repos');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $config['useragent'] = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
        curl_setopt($ch, CURLOPT_USERAGENT, $config['useragent']);
        $headers[] = 'Accept: application/vnd.github+json';

        $headers[] = 'Authorization: Bearer ' . env('GITHUB_TOKEN') ;
        $headers[] = 'X-Github-Api-Version: 2022-11-28';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        
        curl_close($ch);
        $result = json_decode($result);
    
        foreach($result as $key => $value) {
            $output[$key]['id'] = $value->id;
            $output[$key]['url'] = $value->url;
            $output[$key]['description'] = $value->description;
        }

        return response()->json(json_encode($output));
    }
}
