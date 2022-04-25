<?php


namespace App\Helpers;


class ImageMagick
{
	protected $extension;
	protected $originalImageObject;
	/**
	 * @var $currentImageObject
	 */
	public $currentImageObject;

	private $height;
	private $width;

	/**
	 * Проверка на поддержание расширения картинки Imagick Запись в параметр Imagick инициализация
	 * Image2 constructor.
	 * @param null $data
	 * @throws \ImagickException
	 */
	public function __construct($data = null, $ext = null)
	{
        if(!$ext) {
            $ext = pathinfo($data, PATHINFO_EXTENSION);
        }
        $ext = strtoupper($ext);

		if (empty($data) || empty(\Imagick::queryFormats ($ext)))
		{
			return false;
		}

		if (extension_loaded('imagick') && !empty($data)) {
            $this->currentImageObject = new \Imagick($data);
            $this->extension = strtolower(\Imagick::queryFormats ($ext)[0]);
            $geo = $this->currentImageObject->getImageGeometry();
            $this->height = $geo['height'];
            $this->width = $geo['width'];
			$this->originalImageObject = new \Imagick($data);
		}

	}


	/** Второй вариант  * Проверка на поддержание расширения картинки Imagick Запись в параметр Imagick инициализация
	 * @param $imagePath
	 * @return boolean|ImageMagick
	 * @throws \ImagickException
	 */
	public static function init($imagePath)
	{
		$model = new self();

		$ext = strtoupper(pathinfo($imagePath,PATHINFO_EXTENSION));
		if (empty($imagePath) || empty(\Imagick::queryFormats ($ext)))
		{
			return false;
		}

		if (extension_loaded('imagick') && !empty($imagePath)) {

			if (!empty(\Imagick::queryFormats ($ext)))
			{
				$model->currentImageObject = new \Imagick($imagePath);
				$model->extension = strtolower(\Imagick::queryFormats ($ext)[0]);
			}


			$model->originalImageObject = new \Imagick($imagePath);

			return $model;
		}

	}

	/** Получить расширение изображению
	 * @return bool|string
	 */
	public function getExtension()
	{
		if(!empty($this->extension))
		{
			return $this->extension;
		}
		return false;
	}

	/**
	 * Добавить разширение изображению
	 * @param $extension
	 * @return $this
	 */
	public function setExtension($extension)
	{
		$this->extension = $extension;
		return $this;
	}

	/** получить ширину
	 * @return mixed
	 */
	public function getWidth()
	{
		$this->width = $this->currentImageObject->getImageGeometry()['width'];

		return $this->currentImageObject->getImageGeometry()['width'];
	}

	/** получить высоту
	 * @return mixed
	 */
	public function getHeight()
	{

		$this->height = $this->currentImageObject->getImageGeometry()['height'];

		return $this->currentImageObject->getImageGeometry()['height'];
	}

	// http://a32.me/2012/06/scale-images-to-fit-fill-bounding-box-in-php-using-gd/

	/**
	 * умещаем картинку в контейнер с заданными размерами. не растягиваем.
	 * @param int $width
	 * @param int $height
	 * @return $this
	 */
	public function resizeFitTo( $width, $height )
	{


		$sourceWidth = $this->getWidth();
		$sourceHeight = $this->getHeight();

		if($sourceWidth<=$sourceHeight)
		{
			return $this->resizeByWidth($width)->resizeByHeight($height);
		}
		else
		{
			return $this->resizeByHeight($height)->resizeByWidth($width);
		}

	}

  /**
   * Если ширина больше высоты то ресайзим
   * @param $width
   * @param $height
   * @return Image|object
   */
  public function resizeWidthMoreHeight($width,$height)
  {
    if ($this->getWidth() >= $this->getHeight())
    {
      return $this->resize($width, $height);
    }
    else
    {
      return $this->resizeFitTo($width, $height);
    }
  }

	/** пропорциональная нарезка изображения заданным размерам
	 * @param $width
	 * @param $height
	 * @param bool $enableBackfaceZones
	 * @param array $backfaceRgba
	 * @return $this
	 */
	public function resize($width, $height, $enableBackfaceZones = false, $backfaceRgba = array(0, 0, 0, 100))
	{


		if(empty($width) || empty($height))
		{
			return $this;
		}

		$sourceWidth = $this->getWidth();
		$sourceHeight = $this->getHeight();

		// Try to match destination image by width
		$newWidth = $width;
		$newHeight = round($newWidth * ($sourceHeight / $sourceWidth));

		if($enableBackfaceZones)
		{
			$next = $newHeight > $height;
		}
		else
		{
			$next = $newHeight < $height;
		}

		// If match by width failed and destination image does not fit, try by height
		if($next)
		{
			$newHeight = $height;
			$newWidth = round($newHeight * ($sourceWidth / $sourceHeight));

		}

		$imageObject = $this->currentImageObject;

		$imageObject->resizeImage($newWidth, $newHeight, \Imagick::FILTER_UNDEFINED, 1);

		$this->width = $newWidth;
		$this->height = $newHeight;

		return $this;
	}

	/** точная нарезка изображения заданным размерам
	 * @param $width
	 * @param $height
	 * @return $this
	 */
	public function resizeAssymetric($width, $height)
	{
		$this->height = $height;
		$this->width = $width;

		if(!empty($width) || !empty($height))
		{
			$this->currentImageObject->thumbnailImage($width, $height);
		}

		return $this;
	}

	/** нарезка картинки по ширине
	 * @param $width
	 * @param bool $keep_aspect_ratio
	 * @return $this
	 */
	public function resizeByWidth($width, $keep_aspect_ratio = true)
	{
		if(!$width || $width == $this->getWidth())
		{
			return $this;
		}

		if($keep_aspect_ratio)
		{
			$height = $width / $this->getAspectRatio();
		}
		else
		{
			$height = $this->getHeight();
		}

		$this->width = $width;
		$this->height = $height;

		$this->currentImageObject->resizeImage($width, $height, \Imagick::FILTER_UNDEFINED, 1);

		return $this;
	}

	/** нарезка картинки по высоте
	 * @param $height
	 * @param bool $keep_aspect_ratio
	 * @return $this
	 */
	public function resizeByHeight($height, $keep_aspect_ratio = true)
	{
		if(!$height || $height == $this->getHeight())
		{
			return $this;
		}

		if($keep_aspect_ratio)
		{
			$width = $height * $this->getAspectRatio();
		}
		else
		{
			$width = $this->getWidth();
		}

		$this->width = $width;
		$this->height = $height;

		$this->currentImageObject->resizeImage($width, $height, \Imagick::FILTER_UNDEFINED, 1);

		return $this;
	}

	/**
	 * @param $height
	 * @return $this
	 */
	public function cropFromTop($height)
	{
		$this->crop(null, $height, $position_x = 'center', $position_y = 'top');

		return $this;
	}

	/**
	 * @param $width
	 * @return $this
	 */
	public function cropFromRight($width)
	{
		$this->crop($width, null, $position_x = 'right', $position_y = 'center');

		return $this;
	}

	/**
	 * @param $height
	 * @return $this
	 */
	public function cropFromBottom($height)
	{
		$this->crop(null, $height, $position_x = 'center', $position_y = 'bottom');

		return $this;
	}

	/**
	 * @param $width
	 * @return $this
	 */
	public function cropFromLeft($width)
	{
		$this->crop($width, null, $position_x = 'left', $position_y = 'center');

		return $this;
	}

	/**
	 * @param $width
	 * @param $height
	 * @return $this
	 */
	public function cropFromCenter($width, $height)
	{
		$this->crop($width, $height, $position_x = 'center', $position_y = 'center');

		return $this;
	}

	/**
	 * @param $width
	 * @param $height
	 * @param string $position_x
	 * @param string $position_y
	 * @return $this
	 */
	public function crop($width, $height, $position_x = 'center', $position_y = 'center')
	{
		$newWidth = $width;
		$newHeight = $height;

		$originalWidth = $this->getWidth();
		$originalHeight = $this->getHeight();
		// Calculate horisontal cropping position
		switch(strtolower($position_x))
		{
			case 'left':
				$start_x = 0;
				break;
			case 'right':
				$start_x = $originalWidth - $width;
				break;
			case 'center':
			case 'middle':
				$start_x = ($originalWidth / 2) - ($width / 2);
				break;
			default:
				$start_x = (is_numeric($position_x) && $position_x > 0) ? (int) $position_x : 0;
		}
		// Calculate vertical cropping position
		switch (strtolower($position_y)) {
			case 'top':
				$start_y = 0;
				break;
			case 'bottom':
				$start_y = $originalHeight - $height;
				break;
			case 'center':
			case 'middle':
				$start_y = ($originalHeight / 2) - ($height / 2);
				break;
			default:
				$start_y = (is_numeric($position_y) && $position_y > 0) ? (int) $position_y : 0;
		}

		$this->currentImageObject->cropImage($originalWidth, $originalHeight, $start_x, $start_y);
		$this->currentImageObject->resizeImage($newWidth, $newHeight, \Imagick::FILTER_UNDEFINED, 1);

		return $this;
	}

	/*public function setBackgroundColor($bg_color)
	{
		$this->currentImageObject->setBackgroundColor($bg_color);
		$this->currentImageObject->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
		$this->currentImageObject->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
		return $this;
	}*/

	/** вращение картинки , по заданному углу
	 * @param int $angle
	 * @param null $bg_color
	 * @return $this
	 */
	public function rotate($angle = 90, $bg_color = "#fff")
	{
		$this->currentImageObject->rotateImage($bg_color,$angle);
		return $this;
	}

	/**
	 * Creates a horizontal mirror image
	 * @return $this
	 */
	public function mirrorHorisontally()
	{
		$this->currentImageObject->flopImage();
		return $this;
	}

	/**
	 * Creates a vertically mirror image
	 * @return $this
	 */
	public function mirrorVertically()
	{
		$this->currentImageObject->flipImage();
		return $this;
	}

	/**
	 * @return $this
	 */
	public function mirror()
	{
		$this->mirrorHorisontally();
		return $this;
	}

	/**
	 * DEPRECATED
	 * @param int $opacity
	 * @return $this
	 */
	private function setOpacity($opacity = 1)
	{
		$this->currentImageObject->setImageOpacity($opacity);

		return $this;
	}


	public  function createWatermarkCenter($overlay,$params = [])
	{
		$this->watermark($overlay,$params);
		return $this;
	}

	/** Создание ватермарка
	 * default params:
	 * 		$params['anchor'=>'center', $params['x'] => 0 ,$params['y'] => 0]
	 * @param string $overlay
	 * @param int $width
	 * @param int $height
	 * @param array $params
	 * @return $this
	 * @throws \ImagickException
	 */
	public function watermark($overlay,$params = [])
	{

		if ($overlay instanceof self || !file_exists($overlay))
		{
			return $this;
		}
		if(!$overlay instanceof self && file_exists($overlay))
		{
				$watermark = new \Imagick();
				$watermark->readImage($overlay);
				$composite = \Imagick::COMPOSITE_OVER;

				$max_watermark_width = floor($this->width * 0.8);
				$max_watermark_height = floor($this->height * 0.8);

				if (!empty($params['width']) && !empty($params['height']))
				{
					$watermark->resizeImage($params['width'],$params['height'],\Imagick::FILTER_UNDEFINED, 1);
				}
				else if ($watermark->getImageWidth() > $max_watermark_width || $watermark->getImageHeight() > $max_watermark_height) {

					$new_width = $watermark->getImageWidth();
					$new_height = $watermark->getImageHeight();

					if ( $watermark->getImageWidth() > $max_watermark_width ) {
						$ratio = $max_watermark_width / $new_width;
						$new_width = floor( $new_width * $ratio);
						$new_height = floor( $new_height * $ratio);
					}

					if (  $new_height > $max_watermark_height ) {
						$ratio = $max_watermark_height / $new_height;
						$new_width = floor( $new_width * $ratio);
						$new_height = floor( $new_height * $ratio);
					}

					if( $new_width != $watermark->getImageWidth() || $new_height != $watermark->getImageHeight() ) {
						$watermark->resizeImage($new_width, $new_height, \Imagick::FILTER_UNDEFINED, 1);
					}
				}

				$watermarkWidth = $watermark->getImageWidth();
				$watermarkHeight = $watermark->getImageHeight();

				if (!empty($params['opacity']))
				{
					$watermark->setImageAlphaChannel(\Imagick::ALPHACHANNEL_BACKGROUND);
					$watermark->evaluateImage(\Imagick::EVALUATE_DIVIDE, $params['opacity'], \Imagick::CHANNEL_OPACITY);
					$composite = \Imagick::COMPOSITE_BLEND;
				}

				if ($watermark->getNumberImages() !== 1) {
					throw new \ImagickException('not support multiple iterations: `:file`', ['file' => $overlay]);
				}


		}

		$params['anchor'] = !empty($params['anchor'])?$params['anchor']:"center";
		$params['x'] = !empty($params['x'])?$params['x']:0;
		$params['y'] = !empty($params['y'])?$params['y']:0;



		switch($params['anchor'])
		{
			case 'left top':
				$x = $params['x'];
				$y = $params['y'];
				break;
			case 'right top':
				$x = $this->getWidth() - $watermarkWidth + $params['x'];
				$y = $params['y'];
				break;
			case 'top':
				$x = ($this->getWidth() / 2) - ($watermarkWidth / 2) + $params['x'];
				$y = $params['y'];
				break;
			case 'left bottom':
				$x = $params['x'];
				$y = $this->getHeight() - $watermarkHeight + $params['y'];
				break;
			case 'right bottom':
				$x = $this->getWidth() - $watermarkWidth + $params['x'];
				$y = $this->getHeight() - $watermarkHeight + $params['y'];
				break;
			case 'bottom':
				$x = ($this->getWidth() / 2) - ($watermarkWidth / 2) + $params['x'];
				$y = $this->getHeight() - $watermarkHeight + $params['y'];
				break;
			case 'left':
				$x = $params['x'];
				$y = ($this->getHeight() / 2) - ($watermarkHeight / 2) + $params['y'];
				break;
			case 'right':
				$x = $this->getWidth() - $watermarkWidth + $params['x'];
				$y = ($this->getHeight() / 2) - ($watermarkHeight / 2) + $params['y'];
				break;
			default:
				$x = ($this->getWidth() / 2) - ($watermarkWidth / 2) + $params['x'];
				$y = ($this->getHeight() / 2) - ($watermarkHeight / 2) + $params['y'];
				break;
		}

			$this->currentImageObject->compositeImage($watermark, $composite, $x, $y);

		$watermark->clear();
		$watermark->destroy();
		return $this;
	}

	/**
	 * Получить оригинальный объект изображения
	 * @return \Imagick
	 */
	public function getOriginalImageObject()
	{
		return $this->originalImageObject;
	}

	/**
	 * Получить текущий объект изображения
	 * @return \Imagick
	 */

	public function getCurrentImageObject()
	{
		return $this->currentImageObject;
	}

	/**
	 * Получить пропорциональную высоту заданной высоте
	 * @param $width
	 * @return float|int
	 */
	public function getAspectHeight($width)
	{
		$height = $width / $this->getAspectRatio();
		return $height;
	}

	/**
	 * Получить пропорциональную  ширину заданной высоте
	 * @param $height
	 * @return float|int
	 */
	public function getAspectWidth($height)
	{
		$width = $height * $this->getAspectRatio();
		return $width;
	}

	/**
	 * Получить пропорции
	 * @return float|int
	 */
	public function getAspectRatio()
	{
		return $this->getWidth() / $this->getHeight();
	}

	public function scale($scale)
	{
	  /* @var $this->currentImageObject Imagick */
		$width = $this->getWidth() * $scale;
		$height = $this->getHeight() * $scale;
		$this->currentImageObject->scaleImage($height,$width);
		/*$this->resizeAssymetric($width, $height);*/

		return $this;
	}


	public function saveToPath($path,$quality = 70)
	{
		$image = $this->currentImageObject;

		if ($this->extension == 'jpg' || $this->extension == 'jpeg' /*|| $this->extension == 'webp'*/)
		{
			$image->stripImage();
			$image->setImageCompressionQuality($quality);
		}
		if ( $this->extension == 'webp' ){
//      $image->setImageFormat('webp');
//      $image->setImageAlphaChannel(\Imagick::ALPHACHANNEL_ACTIVATE);
//      $image->setBackgroundColor(new \ImagickPixel('transparent'));
//      $image->setOption('webp:method', '6');
      $image->setImageFormat('webp');
      $image->setImageCompressionQuality(50);
      $image->setOption('webp:lossless', 'true');

    }

		$image->writeImage($path);
		$image->clear();
    $image->destroy();

    //x00 webp generation fix
    if (filesize($path) % 2 == 1) {
      file_put_contents($path, "\0", FILE_APPEND);
    }

		return true;
	}

	public function getCompression()
	{
		return $this->currentImageObject->getCompression();
	}

	public function getCompressionQuality (){
		return $this->currentImageObject->getCompressionQuality();
	}

	public function getFullName()
	{
		return $this->getInfoImage()['full_name'];
	}

	public function getInfoImage()
	{
		$file = new FileHelper($this->currentImageObject->getImageFilename());
		$baseName = $file->getBasename();
		$size = $file->getFileSize() / 1024 / 1024;

		$infoImage = [
			'base_name' => $baseName,
			'file_size' => $size,
			'extention' => $this->extension,
			'full_name' => $baseName .".". $this->extension,
		];

		return $infoImage;
	}

	/** convertToWebp $this->ConvertImage('webp',['webp:method'=>'6'],$quality);
	 * @param $setFormat
	 * @param array $options
	 * @param int $quality
	 * @return $this
	 */
	public function ConvertImage($setFormat,$options = [],$quality = 70)
	{
			$image = $this->currentImageObject;

			$image->stripImage();
			$image->setImageCompressionQuality($quality);
			$image->setImageFormat( $setFormat ); // webp
			foreach ($options as $key => $option)
			{
				$image->setOption($key,$option); //'webp:method', '6'
			}

			$this->extension = $setFormat;

			return $this;
	}

	/**
	 * TODO Не работает
	 * @param int $quality
	 * @return string
	 */
	public function saveToBase64($quality = 100)
	{
		ob_start();
		$this->saveToPrint($quality);
		$bin = ob_get_clean();
		$b64 = base64_encode($bin);

		return 'data:image/'.$this->extension.';base64,'.$b64;
	}

	public function saveToPrint($quality = null){
    if ($this->extension == 'jpg' || $this->extension == 'jpeg')
    {
      header("Content-Type: image/jpeg");
    }
    else if ($this->extension == 'jpg' || $this->extension == 'jpeg')
    {
      header("Content-Type: image/png");
    }
    else if ( $this->extension == 'webp' ){

    }

    echo $this->currentImageObject->getImageBlob();
  }

	public function fitCrop($width, $height)
	{
    if ( $this->extension == 'webp' ){
      return $this
        ->resizeWidthMoreHeight($width,$height)
        ->crop($width,$height);
    }

	  return $this->currentImageObject->cropThumbnailImage($width, $height);

//		if ($this->getWidth() >= $this->getHeight() || $height === false) {
//			$this->currentImageObject->thumbnailImage($width, 0);
//		} else {
//			$this->currentImageObject->thumbnailImage(0, $height);
//		}
//		return $this;
	}

	public function blur($radius, $delta)
	{
		$this->currentImageObject->blurImage($radius, $delta);
		return $this;
	}

	public function border($width, $color)
	{
		$border = new \ImagickDraw();
		$border->setFillColor('none');
		$border->setStrokeColor(new \ImagickPixel($color));
		$border->setStrokeWidth($width);
		$widthPart = $width / 2;
		$border->line(0, 0 + $widthPart, $this->getWidth(), 0 + $widthPart);
		$border->line(0, $this->getHeight() - $widthPart, $this->getWidth(), $this->getHeight() - $widthPart);
		$border->line(0 + $widthPart, 0, 0 + $widthPart, $this->getHeight());
		$border->line($this->getWidth() - $widthPart, 0, $this->getWidth() - $widthPart, $this->getHeight());
		$this->currentImageObject->drawImage($border);
		return $this;
	}

  public function reset()
  {
    $this->currentImageObject = $this->originalImageObject;
    return $this;
  }
}
