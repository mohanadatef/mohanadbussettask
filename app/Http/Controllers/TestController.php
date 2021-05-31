<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $response = Http::get('https://api.github.com/search/repositories?q=created:>2019-01-10&sort=stars&order=desc')->json();
        $arr = $response['items'];
        foreach ($arr as $key => $data)
        {
            if(isset($request->programming_language) && !empty($request->programming_language))
            {
                if (strtolower($request->programming_language) != strtolower($data['language'])) {
                    unset($arr[$key]);
                }
            }
        }
        if (isset($request->sort)) {
            $column_sort = array_column($arr, 'stargazers_count');
            array_multisort($column_sort, SORT_ASC, SORT_REGULAR, $arr);
        }
        return $arr;
    }
}
