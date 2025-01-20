<?php
// Mover a register.php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        $hostname = "localhost";
        $dbname = "ieti_tinder";
        $dbUsername = "ietitinder";
        $pw = "tinder123";
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$dbUsername", "$pw");
        
        // Update user status to active
        $query = "UPDATE users SET account_status = 'active' WHERE email_user = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        
        echo "Your account has been verified successfully! You can now <a href='login.php'>login</a>.";
    } catch (PDOException $e) {
        echo "Error verifying account: " . $e->getMessage();
    }
} else {
    echo "Invalid verification link.";
}
?>