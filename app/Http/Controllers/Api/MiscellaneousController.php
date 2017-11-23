<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client; 

class MiscellaneousController extends Controller {

  public function backgroundUrl() {
    $client = new Client();

    # todo
    # learn how to handle exceptions
    $response = $client->get('http://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=10');

    $data = json_decode($response->getBody(), TRUE);

    if( isset( $data['images'] ) )
      $img = rand(0, sizeof( $data['images'] ) -1 );
    else
      return 'not found';

    if( isset( $data['images'][$img]['url'] ) )
      return "http://www.bing.com" . $data['images'][$img]['url'];
    else
      return 'not found';
  }
}
