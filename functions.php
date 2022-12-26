<?php

    $conn = mysqli_connect("localhost","root","","db_kasir");


    function registrasi($data){
        global $conn;

        $username = strtolower(stripslashes($data["username"]));
        $password = mysqli_real_escape_string($conn,$data["password"]);
        $password2 = mysqli_real_escape_string($conn,$data["password2"]);
        $toko     = $data["toko"];
        $alamat     = $data["alamat"];
        $telepon     = $data["telepon"];
        $logo       = $data["logo"];

        if ($password !== $password2){
            echo "<script>
                alert('konfimarsi password tidak sesuai');
            </script>";
            
            return false;
        }
        
        $password = password_hash($password, PASSWORD_DEFAULT);
        
        mysqli_query($conn, "INSERT INTO login VALUES ('','$username','$password','$toko','$alamat','$telepon','$logo')");

        return mysqli_affected_rows($conn);


}


?>