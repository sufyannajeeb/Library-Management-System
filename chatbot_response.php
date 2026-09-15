<?php 
include 'admin/db_connect.php'; // Correct path for db connection 

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get user message from AJAX 
$userMessage = isset($_POST['message']) ? strtolower(trim($_POST['message'])) : ''; 

// Function to create clickable genre links
function createGenreLinks($genres) {
    $links = '';
    foreach ($genres as $genre) {
        $links .= "<a href='#' onclick='sendGenre(\"$genre\")'>$genre</a><br>";
    }
    return $links;
}

// Function to get a random response from an array
function getRandomResponse($responses) {
    return $responses[array_rand($responses)];
}

// Responses for greetings
$greetings = [
    "Hello there! How can I assist you today? 😊 You can ask me for book recommendations by genre!",
    "Hi! How can I help you? Feel free to ask for book recommendations!",
    "Hey! What can I do for you today? Need book suggestions?"
];

// Responses for gratitude
$thanksResponses = [
    "You're welcome! If you need more help, just ask!",
    "My pleasure! Let me know if there's anything else I can do for you.",
    "Glad to be of help! If you have more questions, I'm here for you."
];

// Responses for interesting facts
$interestingFacts = [
    "Did you know? The longest novel ever written is 'In Search of Lost Time' by Marcel Proust, with over 1.2 million words!",
    "Here's a fun fact: The most expensive book ever sold was 'Codex Leicester' by Leonardo da Vinci, which went for $30.8 million!",
    "Interesting fact: The first printed book was the Gutenberg Bible, published in 1455.",
    "Fun fact: The shortest war in history was between Britain and Zanzibar on August 27, 1896. Zanzibar surrendered after 38 minutes!"
];

// Responses for generic questions or unrecognized inputs
$genericResponses = [
    "I'm not sure about that. Could you ask me something else or request book recommendations?",
    "I didn't catch that. Maybe ask me for book suggestions or try another question.",
    "Sorry, I didn't understand that. Feel free to ask me about book genres or anything else!"
];

// Hardcoded responses for specific genres
$genreRecommendations = [
    "novels" => "Here are some popular novels: 'Pride and Prejudice', 'Moby Dick', 'The Great Gatsby', '1984'.",
    "horror" => "Feeling brave? Check out these horror books: 'It' by Stephen King, 'The Haunting of Hill House', 'Dracula', 'Frankenstein'.",
    "romance" => "Romantic reads include: 'Pride and Prejudice', 'Outlander', 'The Notebook', 'Me Before You'.",
    "science fiction" => "Explore the stars with these Sci-Fi books: 'Dune', 'Ender's Game', 'The Left Hand of Darkness', 'Foundation'.",
    "fantasy" => "Magical worlds await in these fantasy books: 'Harry Potter', 'The Hobbit', 'A Game of Thrones', 'The Name of the Wind'.",
    "thriller" => "Thrillers to keep you on edge: 'Gone Girl', 'The Girl with the Dragon Tattoo', 'The Da Vinci Code', 'Sharp Objects'.",
    "mystery" => "Get your detective hat on with: 'Sherlock Holmes', 'Agatha Christie', 'Big Little Lies', 'The Girl on the Train'.",
    "historical fiction" => "Travel back in time with these: 'The Book Thief', 'All the Light We Cannot See', 'Wolf Hall', 'The Nightingale'.",
    "biography" => "Learn from the lives of greats: 'The Diary of a Young Girl', 'Steve Jobs', 'Becoming', 'The Wright Brothers'.",
    "self-help" => "Inspiring reads: 'Atomic Habits', 'The Power of Now', 'How to Win Friends and Influence People', 'The Subtle Art of Not Giving a F*ck'.",
    "children" => "For the young readers: 'Charlotte's Web', 'Matilda', 'Where the Wild Things Are', 'Harry Potter'.",
    "adventure" => "Ready for adventure? Try: 'The Adventures of Huckleberry Finn', 'Treasure Island', 'Into the Wild', 'The Alchemist'.",
    "poetry" => "Feel the emotions with these: 'The Sun and Her Flowers', 'Milk and Honey', 'The Waste Land', 'Leaves of Grass'.",
    "comedy" => "Need a laugh? Try these: 'Good Omens', 'Catch-22', 'The Hitchhiker's Guide to the Galaxy', 'Bossypants'.",
    "drama" => "Emotional reads: 'A Streetcar Named Desire', 'Death of a Salesman', 'The Kite Runner', 'To Kill a Mockingbird'.",
    "classics" => "Timeless reads: 'To Kill a Mockingbird', '1984', 'The Catcher in the Rye', 'Moby Dick'.",
];

// Check for specific questions or greetings
if (in_array($userMessage, ['hi', 'hello', 'how are you', 'hey'])) {
    $response = getRandomResponse($greetings);
} elseif (in_array($userMessage, ['genres', 'available genres', 'list genres'])) {
    // Fetch all genres
    $genreQuery = "SELECT cat_name FROM category";
    $genreResult = $conn->query($genreQuery);

    if ($genreResult->num_rows > 0) {
        $genres = [];
        while ($row = $genreResult->fetch_assoc()) {
            $genres[] = $row['cat_name'];
        }
        $response = "Here are the available genres:<br>" . createGenreLinks($genres);
    } else {
        $response = "Sorry, I couldn't retrieve the genres at the moment.";
    }
} elseif (isset($genreRecommendations[$userMessage])) {
    $response = $genreRecommendations[$userMessage];
} elseif ($userMessage === 'do you know ummu') {
    $response = "ummukulsu MS is the greatest Monna of All time 😎📚";
} elseif (in_array($userMessage, ['i don\'t know', 'i dono', 'help me', 'yes', 'can you help me'])) {
    $response = "Absolutely! I'm here to help you. Just let me know what you're looking for, whether it's book recommendations or something else!";
} elseif ($userMessage === 'books') {
    $response = "Great! What kind of books do you like? Please provide a genre.";
} elseif (in_array($userMessage, ['thank you', 'thanks', 'thanks a lot', 'thank you so much'])) {
    $response = getRandomResponse($thanksResponses);
} elseif ($userMessage === 'facts' || $userMessage === 'interesting facts' || $userMessage === 'hmm') {
    $response = getRandomResponse($interestingFacts);
} else {
    // Check if the user has specified a genre after asking for books
    $query = "SELECT cat_name FROM category WHERE LOWER(cat_name) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $userMessage);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If a genre is found, recommend books
        $category = $result->fetch_assoc()['cat_name'];
    
        // Fetch books related to that genre
        $bookQuery = "SELECT DISTINCT b.book_name FROM books b JOIN category c ON b.cat_id = c.cat_id WHERE LOWER(c.cat_name) = ?";
        $bookStmt = $conn->prepare($bookQuery);
        $bookStmt->bind_param("s", $userMessage);
        $bookStmt->execute();
        $bookResult = $bookStmt->get_result();
    
        if ($bookResult->num_rows > 0) {
            $response = "<div style='font-family: Arial, sans-serif;'>";
            $response .= "<h2>Recommended Books for the '$category' Genre</h2>";
            $response .= "<ul style='list-style-type: square; padding-left: 20px;'>";
            $bookNames = []; // To store unique book names
            while ($row = $bookResult->fetch_assoc()) {
                $bookNames[] = htmlspecialchars($row['book_name']); // Ensure safe output
            }
            // Remove duplicates
            $uniqueBookNames = array_unique($bookNames);
            foreach ($uniqueBookNames as $bookName) {
                $response .= "<li>$bookName</li>";
            }
            $response .= "</ul>";
            $response .= "</div>";
        } else {
            $response = "<div style='font-family: Arial, sans-serif;'>";
            $response .= "<p>Sorry, I couldn't find any books in the '$category' genre.</p>";
            $response .= "</div>";
        }
    } else {
        // If no matching genre, suggest user ask for available genres
        $response = "<div style='font-family: Arial, sans-serif;'>";
        $response .= "<p>I didn't recognize that genre. You can ask me for book recommendations by genre.</p>";
        $response .= "</div>";
    }
    
    // Close prepared statements
    $stmt->close();
    if (isset($bookStmt)) {
        $bookStmt->close();
    }
}

// Send the response back to the user
echo $response;

// Close the database connection
$conn->close(); 
?>
