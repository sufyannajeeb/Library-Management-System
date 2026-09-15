<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="16x16" href="images/l.jpg">
    <title>AudioBook Library</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Header Styling */
        .header {
            text-align: center;
            background-image: linear-gradient(to right, #007bff, #00c6ff);
            padding: 30px 20px;
            color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2.5em;
            margin: 0;
            animation: slideIn 1s ease-in-out;
        }

        .header p {
            font-size: 1.1em;
            margin-top: 10px;
            opacity: 0.8;
            animation: fadeIn 2s ease-in-out;
        }

        /* Animation for Header */
        @keyframes slideIn {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        /* Container for audiobooks */
        .container {
            width: 90%;
            margin: 50px auto;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }

        .audiobook {
            background-color: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
            width: 300px;
            position: relative;
        }

        .audiobook:hover {
            transform: translateY(-10px);
            box-shadow: 0px 12px 40px rgba(0, 0, 0, 0.2);
        }

        .thumbnail {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .audiobook:hover .thumbnail {
            transform: scale(1.05);
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .title {
            font-size: 1.4em;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
            transition: color 0.3s ease-in-out;
        }

        .audiobook:hover .title {
            color: #007bff;
        }

        .description {
            font-size: 1em;
            color: #777;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        /* Custom Audio Player Styling */
        .audio-player-container {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .audio-player {
            display: none; /* Hide the default audio controls */
        }

        .play-button {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #007bff, #00c6ff);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 1.5em;
            cursor: pointer;
            outline: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease, transform 0.3s ease;
            margin-left: 10px;
        }

        .play-button:hover {
            background: linear-gradient(45deg, #0056b3, #0083c1);
            transform: translateY(-2px);
        }

        .play-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Mobile responsiveness */
        @media(max-width: 600px) {
            .container {
                flex-direction: column;
                align-items: center;
            }
        }

        .back {
            display: inline-block;
            padding: 8px 16px;
            font-size: 1rem;
            font-weight: 800;
            color: #ffffff;
            background-color: #007bff; /* Blue background */
            border: none;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 25px;
        }

        .back:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .back:active {
            background-color: #004085; /* Even darker blue on click */
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

    </style>
</head>
<body>
    <a href="#" class="back" onclick="goBack()">Back</a>
    <div style="text-align: center;">
        <h1>AudioBook Library</h1>
        <p>Experience stories that will captivate your mind and soul</p>
    </div>

    <div class="container">
        <?php
        // Array of audiobooks with thumbnails, titles, descriptions, and audio file URLs
        $audiobooks = [
            [
                "thumbnail" => "images/26.jpg",
                "title" => "The Great Indian Train Journey",
                "description" => "By Ruskin Bond is a vivid recounting of India's diverse landscapes, people, and experiences, as seen through the lens of a train ride.",
                "audio" => "Audio/019-railwayjourney_middleton.mp3"
            ],
            [
                "thumbnail" => "images/benson_murder_case_2202.jpg",
                "title" => "The Benson Murder Case",
                "description" => "By S.S. Van Dine is a classic detective novel featuring the amateur sleuth Philo Vance. It revolves around the investigation of a high-profile murder in New York",
                "audio" => "Audio/bensonmurdercase_01_vandine_128kb.mp3"
            ],
            [
                "thumbnail" => "images/beyondrope_2405.jpg",
                "title" => "Beyond Rope",
                "description" => "By Elma Napier is a reflective novel set in Dominica, exploring themes of identity, belonging, and the complexities of colonial life.",
                "audio" => "Audio/beyondropeandfence_00_grew_128kb.mp3"
            ],
            [
                "thumbnail" => "images/dunwichhorror_1511.jpg",
                "title" => "The Dunwich Horror",
                "description" => "By H.P. Lovecraft is a horror story set in the fictional town of Dunwich, where strange and terrifying events unfold due to dark, occult rituals.",
                "audio" => "Audio/dunwichhorror_01_lovecraft_128kb.mp3"
            ],
            [
                "thumbnail" => "images/history_henryiv_france_1910.jpg",
                "title" => "History of Henry the Fourth",
                "description" => "Is a Play by William Shakespeare that chronicles the reign of King Henry IV of England.",
                "audio" => "Audio/henrythefourth_01_abbott_128kb.mp3"
            ],
            [
                "thumbnail" => "images/Comic_History_England_1109.jpg",
                "title" => "Comic History of England",
                "description" => "By Gilbert Abbott à Beckett is a satirical take on England's history, blending humor with exaggerated commentary.",
                "audio" => "Audio/comic_history_england_00_nye.mp3"
            ],
            [
                "thumbnail" => "images/arrow_gold_2103.jpg",
                "title" => "Arrow Gold",
                "description" => "Series by Wilbur Smith, centered on ancient Egypt, combining adventure, political intrigue, and romance.",
                "audio" => "Audio/arrowofgold_00_conrad_128kb.mp3"
            ],
            [
                "thumbnail" => "images/makeshifts_realities_2408.jpg",
                "title" => "Makeshifts Real",
                "description" => "By Thomas Hardy is a poignant exploration of life, love, and the complexities of human relationships set against the backdrop of rural England.",
                "audio" => "Audio/makeshiftsreal_00_hardy_128kb.mp3"
            ],
           
            // Add more audiobooks as needed
        ];

        foreach ($audiobooks as $book) {
            echo '<div class="audiobook">';
            echo '<img src="' . $book["thumbnail"] . '" alt="' . $book["title"] . '" class="thumbnail">';
            echo '<div class="content">';
            echo '<h2 class="title">' . $book["title"] . '</h2>';
            echo '<p class="description">' . $book["description"] . '</p>';
            echo '<div class="audio-player-container">';
            echo '<audio class="audio-player" id="audio-' . $book["title"] . '">';
            echo '<source src="' . $book["audio"] . '" type="audio/mpeg">';
            echo 'Your browser does not support the audio tag.';
            echo '</audio>';
            echo '<button class="play-button" onclick="togglePlay(\'' . $book["title"] . '\')">&#9658;</button>'; // Play button
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>

    <script>
    let currentlyPlaying = null; // Track the currently playing audio

    function togglePlay(title) {
        const audio = document.getElementById(`audio-${title}`);
        const playButton = document.querySelector(`.play-button[onclick*='${title}']`);

        // Pause the currently playing audio if it's not the one being clicked
        if (currentlyPlaying && currentlyPlaying !== audio) {
            currentlyPlaying.pause();
            currentlyPlaying.currentTime = 0; // Reset the audio
            const currentButton = document.querySelector(`.play-button[onclick*='${currentlyPlaying.dataset.title}']`);
            currentButton.innerHTML = '&#9658;'; // Change to play icon
        }

        // Toggle play/pause for the selected audio
        if (audio.paused) {
            audio.play();
            playButton.innerHTML = '&#10074;&#10074;'; // Change to pause icon
            currentlyPlaying = audio; // Update currently playing
        } else {
            audio.pause();
            playButton.innerHTML = '&#9658;'; // Change to play icon
            currentlyPlaying = null; // Reset currently playing
        }
    }

    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>

                