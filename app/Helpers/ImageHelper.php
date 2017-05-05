<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageHelper
{
	public static function compressImage($filename)
	{
		if (strpos($filename, '.jpeg')) {
			$image = Image::make(Storage::get($filename));
			$image = self::orientate($image, 'storage/' . $filename);
			$image->save('storage/' . $filename, 25);
			return $filename;
		}
		return "";
	}

	private static function orientate($image, $fileName)
	{
		$orientation = @read_exif_data($fileName, '*')['Orientation'] ?? 0;
		switch ($orientation) {
			case 1:
				return $image;
			case 2:
				return $image->flip('h');
			case 3:
				return $image->rotate(180);
			case 4:
				return $image->rotate(180)->flip('h');
			case 5:
				return $image->rotate(-90)->flip('h');
			case 6:
				return $image->rotate(-90);
			case 7:
				return $image->rotate(-90)->flip('v');
			case 8:
				return $image->rotate(90);
			default:
				return $image;
		}
	}
}