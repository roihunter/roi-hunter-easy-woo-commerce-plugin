<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class RH_Easy_Api_Client {

    function uninstall($access_token) {
        $this->do_exchange('uninstall', $access_token);
    }

    private function do_exchange($path, $access_token) {
        $request_url = 'https://goostav.roihunter.com/' . $path;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $request_url,
            CURLOPT_HTTPHEADER => ['X-Authorization: ' . $access_token]
        ]);

        curl_exec($curl);

        curl_close($curl);
    }
}