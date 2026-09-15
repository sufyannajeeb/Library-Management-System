<?php
// Function to hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Path to the original SQL dump file
$inputFile = 'D:/xampp2/htdocs/lmsf/lms.sql';
// Path to the new SQL dump file with hashed passwords
$outputFile = 'D:/xampp2/htdocs/lmsf/lms_dump_hashed.sql';

// Check if the input file exists
if (!file_exists($inputFile)) {
    die("Error: The file $inputFile does not exist.\n");
}

// Read the original SQL file
$sql = file_get_contents($inputFile);

if ($sql === false) {
    die("Error reading the SQL file $inputFile.\n");
}

// Array of user passwords to hash (passwords in the same order as in the SQL dump)
$userPasswords = [
    'n123'  // Add more passwords here if needed
];

// Replace plain passwords with hashed passwords
foreach ($userPasswords as $password) {
    $hashedPassword = hashPassword($password);
    $sql = str_replace("'" . $password . "'", "'" . $hashedPassword . "'", $sql);
}

// Save the new SQL to a file
file_put_contents($outputFile, $sql);

echo "User passwords have been hashed and saved to $outputFile\n";
?>



