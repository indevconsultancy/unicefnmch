<?php
require_once 'config.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateToken($userId, $secretKey, $clientId)
{
    // Generate JWT token
    $payload = [
        'user_id' => $userId,
        'user_taken' => $clientId,
        // 'exp' => time() + (2880 * 2880), // Token expiration time (48 hour)
		'exp' => time() + (30 * 24 * 60 * 60), // Token expiration time (30 days)
        'nbf' => time()
    ];
    return JWT::encode($payload, $secretKey, 'HS256');
}

function verifyToken($token, $secretKey, $conn = null)
{
    try {
        $userdata = JWT::decode($token,new Key($secretKey, 'HS256'));
        if($conn != null){
         $sql = "SELECT `status`, `name` FROM `users` WHERE user_id='" . $userdata->user_id . "' limit 1 ";
         $run = mysqli_query($conn, $sql);
         $data = mysqli_fetch_object($run);
         if($data->status == 1){
             return false;
         }
        }
        return $userdata;
    } catch (Exception $e) {
        return false;
    }
}

function generateRefreshToken($userId, $secretKey, $clientId)
{
    // Generate refresh token
    $payload = [
        'user_id' => $userId,
        'user_taken' => $clientId,
        'exp' => time() + (364 * 24 * 60 * 60), // Token expiration time (365 days)
        'nbf' => time() 
    ];
    return JWT::encode($payload, $secretKey, 'HS256');
}

function saveTokensToDatabase($userId, $jwtToken, $refreshToken, $con)
{
                $inser_sql = 'INSERT INTO `user_tokens` set `token`="' . $jwtToken . '", `refresh_token`="' . $refreshToken . '",`user_id`=' . $userId;
				$insertquery = mysqli_query($con, $inser_sql);
				$last_id = mysqli_insert_id($con);
		
                if ($insertquery) {
                return $last_id;
                } else{
                    return -1;
                }
}
function updateTokensToDatabase($userId, $jwtToken, $refreshToken, $conn)
{
    $inser_sql = "UPDATE user_tokens set token='" . $jwtToken . "', refresh_token='" . $refreshToken . "', user_id='" . $userId . "' where refresh_token='" . $refreshToken . "'  ";			
    return mysqli_query($conn, $inser_sql);
}
function deleteTokensFromDatabase($refreshToken, $conn)
{
    $delete_sql = "DELETE FROM user_tokens where refresh_token='" . $refreshToken . "'  ";			
    return mysqli_query($conn, $delete_sql);
}