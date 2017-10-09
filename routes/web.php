<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => 1,        'redirect_uri' => 'http://127.0.0.1:9000/callback',        'response_type' => 'code',        'scope' => '',    ]);
    return redirect('http://127.0.0.1:8000/oauth/authorize?'.$query);});
Route::get('/callback', function (Request $request) {

    $http = new GuzzleHttp\Client;
    $response = $http->post('http://127.0.0.1:8000/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',            'client_id' => 1,            'client_secret' => 'qvNY5oyoZvcIPPSM8A6ZPOdx9qbXUTCl7IyRxuzx',            'redirect_uri' => 'http://127.0.0.1:9000/callback',            'code' => $request->code,        ],    ]);
    return json_decode((string) $response->getBody(),true);    

});
Route::get('/', function () {
    return view('api');
});

Route::get('/forget-token', function() {

	session()->forget('api-token');
    return redirect('/');

});



Route::get('/callback_old', function () {
    $http = new GuzzleHttp\Client;

    $response = $http->post(env('API_URL').'/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => env('API_CLIENT_ID'),
            'client_secret' => env('API_CLIENT_SECRET'),
            'code' => request()->code,
        ],
    ]);

    $apiResponse = json_decode((string) $response->getBody(), true);
    session(['api'=> $apiResponse]);
    session(['api-token'=> $apiResponse['access_token']]);
    return redirect('/');
});