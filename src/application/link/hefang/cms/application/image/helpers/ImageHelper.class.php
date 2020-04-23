<?php


namespace link\hefang\cms\application\image\helpers;


class ImageHelper
{
	public static function captcha(string $captcha)
	{
		$height = 60;
		$width = 180;
		$img = imagecreatetruecolor($width, $height);
		$colorBg = imagecolorallocate($img, 255, 255, 255);
		imagefill($img, 0, 0, $colorBg);


		for ($i = 0; $i <= max($height, $width); $i++) {
			$pColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
			imagesetpixel($img, rand(0, $width - 1), rand(0, $height - 1), $pColor);
		}

		for ($i = 0, $x = 5; $i < mb_strlen($captcha); $i++) {
			$tColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
			$fontSize = rand(20, 40);
			imagettftext($img, $fontSize, rand(-45, 45), $x, $fontSize + 5, $tColor, "C:\Users\hefang\DevDir\hefang-cms\hefang-cms-php\src\data\\fonts\msyh.ttc", $captcha{$i});
			$x += $fontSize;
		}
		for ($i = 0; $i < min($height, $width) * .1; $i++) {
			$lColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
			imageline($img, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lColor);
		}
		return $img;
	}
}
