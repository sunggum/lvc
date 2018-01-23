<?
// -----------------------
// build_autoviewer_gallerydata v3
// -----------------------
// This script automatically generates the XML document for Autoviewer
// www.airtightinteractive.com/projects/autoviewer/
// Instructions to use are at: 
// www.airtightinteractive.com/projects/autoviewer/auto_server_instructions.html
//
// by: Geoff Smith + Felix Turner


// SET GALLERY OPTIONS HERE
// -----------------------
$options .= '<gallery frameColor="0xFFFFFF" frameWidth="15" imagePadding="20" displayTime="6" enableRightClickOpen="true">';
// Set sortImagesByDate to true to sort by date. Otherwise files are sorted by filename.
$sortImagesByDate = false;
// Set sortInReverseOrder to true to sort images in reverse order.
$sortInReverseOrder = false;
// END OF OPTIONS
// -----------------------

print "Creating XML for Autoviewer.<br>"; 
print "-------------------------------------------------<br><br>"; 

if ($sortImagesByDate){
	print "Sorting images by date.<br>";
}else{
	print "Sorting images by filename.<br>";		
}

if ($sortInReverseOrder){
	print "Sorting images in reverse order.<br><br>";
}else{
	print "Sorting images in forward order.<br><br>";		
}

//loop thru images 
$xml = '<?xml version="1.0" encoding="UTF-8" ?>'.$options;
$folder = opendir("images");
while($file = readdir($folder)) {
	if ($file[0] != "." && $file[0] != ".." && $file[0] != "Thumbs.db" ) {
		if ($sortImagesByDate){
			$files[$file] = filemtime("images/$file");
		}else{
			$files[$file] = $file;
		}
	}		
}	

// now sort by date modified
if ($sortInReverseOrder){
	arsort($files);
}else{
	asort($files);
}

foreach($files as $key => $value) {

	// Get the image dimensions.
	$dimensions = getimagesize('images/'.$key);
	$width = $dimensions[0];
	$height = $dimensions[1];	

	$xml .= "\n<image>\n";
	$xml .= "  <url>images/".$key."</url>\n";
	$xml .= "  <caption></caption>\n";
	$xml .= "  <width>".$width."</width>\n";
	$xml .= "  <height>".$height."</height>\n";
	$xml .= "</image>\n";
	
        print "- Created Image Entry for: $key whose width is ".$width." and whose height is ".$height.".<br />";  
}

closedir($folder);

$xml .= '</gallery>';
//next line can cause erroneous warnings
//chmod( 'gallery.xml', 0777 );
$file = "gallery.xml";   
if (!$file_handle = fopen($file,"w")) { 
	print "<br>Cannot open XML document: $file<br>"; 
}  elseif (!fwrite($file_handle, $xml)) { 
	print "<br>Cannot write to XML document: $file<br>";   
}else{
	print "<br>Successfully created XML document: $file<br>";   
}
fclose($file_handle);		

?>