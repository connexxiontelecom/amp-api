<?php namespace App\Controllers;

class Home extends BaseController {

	function index() {
		return $this->respond('Welcome to AMP-API v0. Powered by your friends at Connexxion Telecom.');
	}
}
