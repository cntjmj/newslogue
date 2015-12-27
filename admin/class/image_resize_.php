<?php 

/**
 * PHP ImgSizer - PHP dynamic image resize class
 * NOTE: Designed for use with PHP version 5 and up
 * @package PHPImgSizer
 * @author Kent Safranski (http://www.fluidbyte.net)
 */

class ImgSizer{
	
	// SET VARIABLES ##############################################################################	
	
	public $image   = "";
	public $width     = 100; // Max width or height (applied to $type)
	public $height    = 100;
	public $quality = 8;
	public $square  = false; // Crop the image in a square (based on center)
	public $prefix  = ""; // Prefix added to resized images
	public $folder  = ""; // Folder for resized images (inside source folder, trailing slash req.)	
	
	
	// RESIZE FUNCTION ############################################################################
	public function settings($image = "",$width = 100,$height = 100,$quality = 8,$square = false,$prefix = "",$folder = ""){
	   $this->image = $image;
       $this->width = $width;
       $this->height = $height;
       $this->quality = $quality;
       $this->square = $square;
       $this->prefix = $prefix;
       $this->folder = $folder;
	}
	public function resize(){		
		
		// Split image parts (path and file)
		$imgSplit = explode("/",$this->image);
		$srcName = end($imgSplit);
		//$srcUrl = str_ireplace($srcName, "", $this->image);
		$srcUrl = str_ireplace($srcName, "", $this->image);
		
		
		//$srcPath = $_SERVER['DOCUMENT_ROOT'] ."/resize/". str_ireplace($srcName, "", $this->image);
		//$srcPath = $this->image;
		$srcPath = $srcUrl;
		// Get extension
        
		$split = explode(".",$srcName);
		$ext = strtolower($split[1]);

		// Misc variables
		$rszUrl = $srcUrl . $this->folder;
		$rszName = $this->prefix . $srcName;
		$rszPath = $this->folder;
		$rszQuality = $this->quality;
		
		// If save path doesn't exist, create it
		//echo "<br />resize Path : $rszPath <br />";
		//echo "<br />and :".$targetImage;
        
		if (!file_exists($rszPath)){ mkdir($rszPath, 0777);	}
			
		// If the resized img doesn't exist, create it
		
		//if(file_exists("$rszPath/$rszName") || !file_exists("$rszPath/$rszName")){ 
			
			switch($ext){
				case('jpg'): $srcImage = imagecreatefromjpeg("$srcPath$srcName"); break;
				case('jpeg'): $srcImage = imagecreatefromjpeg("$srcPath$srcName"); break;
				case('png'): $srcImage = imagecreatefrompng("$srcPath$srcName"); if($rszQuality==10){ $rszQuality=9; } break;
				case('gif'): $srcImage = imagecreatefromgif("$srcPath$srcName"); break;
			}
			
			$srcWidth = imagesx($srcImage);
			$srcHeight = imagesy($srcImage);
			
			// Determine specs based on type
			$rszWidth = $this->width;
			$rszHeight  = $this->height;
			/*if(strtolower($this->type)=="width"){
				$rszWidth = $this->max;
				$rszHeight = $srcHeight/($srcWidth/$rszWidth);
			}
			else{
				$rszHeight = $this->max;
				$rszWidth = $srcWidth/($srcHeight/$rszHeight);
			}
			*/
			
			// Determine specs if crop applied
			
			$srcX = 0; $srcY = 0;
			$srcNewWidth = $srcWidth; $srcNewHeight = $srcHeight;
			$dest = $srcImage;
			
			// Square crop
			
			if($this->square==true){
				$rszWidth = $this->width;
				$rszHeight = $this->height;
				
				if($srcHeight>$srcWidth){
					$srcX = 0;
					$srcY = floor(($srcHeight-$srcWidth)/2);
					$srcNewHeight = $srcWidth;
					$srcNewWidth = $srcWidth;
				}
				
				if($srcWidth>$srcHeight){
					$srcX = floor(($srcWidth-$srcHeight)/2);
					$srcY = 0;
					$srcNewHeight = $srcHeight;
					$srcNewWidth = $srcHeight;
				}
				// Create new image with a new width and height.
				$dest = imagecreatetruecolor($srcNewWidth, $srcNewHeight);
				$this->resize_png($this->image,$srcImage,$dest);
				// Copy new image to memory after cropping.
				imagecopy($dest, $srcImage, 0, 0, $srcX, $srcY, $srcNewWidth, $srcNewHeight);
			}
				
			$targetImage = imagecreatetruecolor($rszWidth,$rszHeight);
			
			/* starts */
			if($ext == 'png'){
				
				$this->resize_png($this->image,$srcImage,$targetImage);
				// Save file, quality=9, Add filters... although sometimes better without.
				//imagepng( $d, substr($rszUrl . $rszName,1), 9, PNG_ALL_FILTERS);
			}
			/* ends */
			
				
			imagecopyresampled($targetImage,$dest,0,0,0,0,$rszWidth,$rszHeight,$srcNewWidth,$srcNewHeight);
			
			switch($ext){
				case('jpg'): imagejpeg($targetImage, "$rszPath/$rszName", $rszQuality * 10); break;
				case('jpeg'): imagejpeg($targetImage, "$rszPath/$rszName", $rszQuality * 10);	break;
				case('png'): imagepng($targetImage, "$rszPath/$rszName", $rszQuality); break;
				case('gif'): imagegif($targetImage, "$rszPath/$rszName"); break;
			}
			
		//}
		
		// Return the resized image
		
		return($rszUrl . $rszName);
		
		// Clear temps
		imagedestroy($dest);
		imagedestroy($targetImage);
		
	}
	
	public function resize_png($img,$srcImage,$targetImage){
		$m = microtime(true);
		
		
		
		// Size
		
		$s = getimagesize( $img );
		
		// Resize dimensions
		$w = 100;
		$h = round($w*($s[1]/$s[0]));
		
		// Source
		//$i = imagecreatefrompng( $img );
		// Destination
		//$d = imagecreatetruecolor($w,$h);
		$i = $srcImage;
		$d = $targetImage; 
		// if this has no alpha transparency defined as an index
		// it could be a palette image??
		$palette = (imagecolortransparent($i)<0);
		
		// If this has transparency, or is defined
		if(!$palette||(ord(file_get_contents ($img, false, null, 25, 1)) & 4)){
			
			// Has indexed transparent color
			if(($tc=imagecolorstotal($srcImage))&&$tc<=256)
				imagetruecolortopalette($d, false, $tc);
			imagealphablending($d, false);
			$alpha = imagecolorallocatealpha($d, 0, 0, 0, 127);
			imagefill($d, 0, 0, $alpha);
			imagesavealpha($d, true);
			//var_dump(microtime(true)-$m);
		}
		
		// Resample Image
		
		imagecopyresampled($d, $srcImage, 0, 0, 0, 0, $w, $h, $s[0], $s[1]);
		//var_dump(microtime(true)-$m);
		
		// Did the original PNG supported Alpha?
		if((ord(file_get_contents ($img, false, null, 25, 1)) & 4)){
			
			// we dont have to check every pixel.
			// We take a sample of 2500 pixels (for images between 50X50 up to 500X500), then 1/100 pixels thereafter.
			$dx = min(max(floor($w/50),1),10);
			$dy = min(max(floor($h/50),1),10);
		
			$palette = true;
			for($x=0;$x<$w;$x=$x+$dx){
				for($y=0;$y<$h;$y=$y+$dy){
					$col = imagecolorsforindex($d, imagecolorat($d,$x,$y));
					// How transparent until it's actually visible
					// I reackon atleast 10% of 127 before its noticeable, e.g. ~13
					if($col['alpha']>13){
						//print_r($col);
						$palette = false;
						break 2;
					}
				}
			}
			//var_dump(microtime(true)-$m);
			//var_dump( !$palette );
		}
		
		if($palette){
			
			imagetruecolortopalette($d, false, 256);
			//var_dump(microtime(true)-$m);
		}
	}
}

?>