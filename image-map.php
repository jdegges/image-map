<?php

$scale = 1;
if(isset($_GET['scale']) && is_numeric($_GET['scale'])) {
    $scale = $_GET['scale'];
}

$width  = 1500;
$height = 1500;
$radius = 8*$scale;

/* print header */
function echo_header() {
    echo "<DIV ALIGN=CENTER>\n";
    echo "\t<MAP NAME=\"image-map\">\n";
}

/* print footer */
function echo_footer() {
    global $width;
    global $height;
    global $scale;

    echo "\t</MAP>\n";
    echo "\t<IMG SRC=\"locations_map_lg.jpg\" ALT=\"Locations Map\" BORDER=0 ";
    echo "WIDTH=".$width*$scale." HEIGHT=".$height*$scale;
    echo " USEMAP=\"#image-map\">\n";
    echo "</DIV>\n";
}

/* print each location */
function echo_locations($locs) {
    global $scale;
    global $radius;
    foreach ($locs as $loc) {
        echo "\t\t<AREA HREF=\"image-map.php?scale=$scale&loc=$loc[0]\" ";
        echo "ALT=\"$loc[0]\" TITLE=\"$loc[0]\" SHAPE=CIRCLE ";
        echo "COORDS=\"".$loc[1]*$scale.", ".$loc[2]*$scale.", $radius\">\n";
    }
}

/* read locations from the file $loc_file
 * each line of the file contains data for one point
 * there are three fields on each line each separated by a comma:
 * first  -> location identifier
 * second -> x coordinate of the center of the circle
 * thrid  -> y coordinate of the center of the circle
*/
function get_locations($loc_file) {
    $locs = array();
    $fh = fopen($loc_file, "rb");

    /* while there is data in the file */
    while(!feof($fh)) {
        /* grab a line from the file */
        $line = fgets($fh);

        /* if the line starts with a '#' or isnt long enough then ignore it */
        if($line[0] == '#' || strlen($line) < 5)
            continue;

        /* convert the line into an array */
        $pts = explode(',', $line);

        /* chop off any whitespace from each field */
        for($i = 0; $i < sizeof($pts); $i++) {
            $pts[$i] = rtrim($pts[$i]);
        }

        /* push new data onto the end of the array */
        array_push($locs, $pts);
    }
    return $locs;
}


echo_header();
$pts = get_locations("locations.txt");
echo_locations($pts);
echo_footer();

?>
