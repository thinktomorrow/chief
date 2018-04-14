<?php

namespace Chief\Assets\Services;

class Cropper{

	private $filepath;
	private $image_type = null;
	private $target_width = 220;
	private $target_height = 220;

	public function __construct($filepath)
	{
		$this->filepath = $filepath;

		$this->validateImage();
	}

	/**
	 * Set the target sizes
	 * 	
	 * @param  int $width  
	 * @param  int $height 
	 * @return self  
	 */
	public function size($width,$height = null)
	{
		$this->target_width = $width;
		$this->target_height = $height?:$width;

		return $this;
	}

	/**
	 * Crop the image
	 * 
	 * @param  int $width  
	 * @param  int $height 
	 * @param  int $x      
	 * @param  int $y      
	 * @return void         
	 */
	public function crop($width,$height,$x = null,$y = null)
	{
		$this->validateTargetSizes($width,$height);

		$x = $this->getX($x,$width);
		$y = $this->getY($y,$height);

		$canvas = imagecreatetruecolor($this->target_width, $this->target_height);

		$image = $this->createImage();
		
		if(false == imagecopyresampled($canvas, $image, 0, 0, $x, $y, $this->target_width, $this->target_height, $width, $height))
		{
			throw new \Exception('failed to crop the image');
		}

		$this->draw($canvas);

		imagedestroy($canvas);
		imagedestroy($image);
	}

	private function validateTargetSizes($width,$height)
	{
		if(!$this->target_width)
		{
			$this->target_width = $width;
		}

		if(!$this->target_height)
		{
			$this->target_height = $height;
		}
	}

	private function getX($x = null,$width)
	{
		if(is_int($x)) return $x;

		return ($this->getHeight() / 2) - ($width / 2);
	}

	private function getY($y = null,$height)
	{
		if(is_int($y)) return $y;

		return ($this->getWidth() / 2) - ($height / 2);
	}

	private function createImage()
	{
		// Create Image Canvas
		switch($this->getImageType())
		{
			case IMAGETYPE_GIF:
				$image = imagecreatefromgif($this->filepath);
			break;
			case IMAGETYPE_JPEG:
				$image = imagecreatefromjpeg($this->filepath);
			break;
			case IMAGETYPE_PNG:
				$image = imagecreatefrompng($this->filepath);
			break;
		}

		return $image;

	}

	private function draw($canvas)
	{
		// Create Image Canvas
		switch($this->getImageType())
		{
			case IMAGETYPE_GIF:
				imagegif($canvas,$this->filepath);
			break;
			case IMAGETYPE_JPEG:
				imagejpeg($canvas,$this->filepath);
			break;
			case IMAGETYPE_PNG:
				imagepng($canvas,$this->filepath);
			break;
		}
	}

	private function getExtension()
	{
		return image_type_to_extension($this->getImageType());
	}

	private function getImageType()
	{
		if($this->image_type) return $this->image_type;

		return $this->image_type = exif_imagetype($this->filepath);
	}

	private function getWidth(){ return $this->getImageSize('width'); }
	private function getHeight(){ return $this->getImageSize('height'); }

	private function getImageSize($key = null)
	{
		list($width,$height) = getimagesize($this->filepath);

		return $key ? $$key : [$width,$height];
	}

	private function validateImage()
	{
		$allowed_types = [IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG];

		if(!in_array($this->getImageType(), $allowed_types))
		{
			throw new InvalidArgumentException('Selected Image type is not supported');
		}
	}

}