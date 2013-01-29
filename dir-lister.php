<?
/*
	Directory Listing Utility
	By Tim Hosey
	http://www.zomgrei.com
*/

	// basic config. $baseDir is the relative directory that it's starting in;
	// $basePath is the absolute directory it's starting in.
	$baseDir = "/";
	$basePath = "c:/xampp/htdocs";

	// function for checking if odd or not
	function isOdd($num) {
		return ($num&1 ? true : false);
	}
	
	// sets $dir to the $_GET['dir'] value if set, null otherwise.
	$dir = (isset($_GET['dir']) ? $_GET['dir'] : null );

	// checks to see if the directory put into the dir arg exists or not.
	// returns false and ends the script otherwise.
	if (!is_dir($basePath."/".$dir)) {
		echo "$baseDir/$dir does not exist. Please try again.";
		return false;
	}
	
	// scandir returns the path contents in an array
	$contents = scandir($basePath."/".$dir);
	// this is the name of our directory lister file.
	$filePath = pathinfo(__FILE__, PATHINFO_FILENAME).".php";
	
	// if our directory isn't empty, it decodes the dir and echoes it as our title.
	// otherwise, it echos the base directory's name
	if ($dir!=null) { $decodedDir = urldecode($dir); echo $decodedDir; } else { $decodedDir = null; echo $baseDir; }
	
	// while the increment # is less than the number of results in our array....
	$i = 0;
	while($i<count($contents)) {
		// if it's odd, we set the class to "alt". Even, set to "primary"
		$class = (isOdd($i) ? 'alt' : 'primary' )
		// as long as the contents are neither . or .. and $dir isn't empty:
		// this is so we don't list . or .. for the base dir - we can't go above the baseDir
		if (!(($contents[$i]=="." || $contents[$i]=="..") && $dir==null)) {
			// checks if $dir isn't empty
			if ($dir!=null) {
				// if this is the link to go up a level
				if ($contents[$i]=="..") {
					// break the $decodedDir var into an array, chunks being separated by /
					$parts = explode("/",$decodedDir); 
					// removes the last entry here so we can go up a level
					unset($parts[count($parts)-1]);
					// urlencodes the array, now arranged into a string separated by /
					$dirUrl = urlencode(implode("/", $parts)); 
				// if this link is to stay on the same level
				} elseif ($contents[$i]==".") {
					// urlencode this directory
					$dirUrl = urlencode($dir);
				// if this link is for anything else
				} else {
					// urlencodes the next directory
					$dirUrl = urlencode($dir."/".$contents[$i]);
				}
			// if $dir is empty
			} else {
				// urlencodes the current increment contents as the next dir
				$dirUrl = urlencode($contents[$i]);
			}
			
			// checks to see if it's a file and exists.
			if (is_file($basePath."/".$decodedDir."/".$contents[$i])) {
				// if $dir is empty, it sets to the base dir, otherwise it sets to the incremented dir
				$fileUrl = ($dir==null ? $fileUrl = $baseDir."/".$contents[$i] : $fileUrl = $baseDir."/".$dir."/".$contents[$i]);
				// generates the link
				echo "<div class='$class'><a href='$fileUrl'>".$contents[$i]."</div>";
			// if it's not a file
			} else {
				// generates the link
				echo "<div class='$class'><a href='$filePath?dir=$dirUrl'>".$contents[$i]."</div>";
			}
		}
		// increment counter
		$i++;
	}

?>