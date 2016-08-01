<?php

namespace Chief\Assets;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface Asset{
	
	/**
	 * Get full asset path
	 * 				
	 * @return string
	 */
	public function getPath();

	/**
	 * Get full asset url
	 * 
	 * @return string
	 */
	public function getUrl();

	/**
	 * Upload new asset to disk
	 *
	 * @param  UploadedFile $uploadFile
	 * @param  string 		$filename
	 * @return void
	 */
	public function upload(UploadedFile $uploadFile = null, $filename);
	
	/**
	 * Remove asset from disk
	 * 
	 * @return void
	 */
	public function remove();
}