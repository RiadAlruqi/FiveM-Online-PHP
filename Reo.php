<?php
            $accessToken = 'Tokenhere'; // حط توكنك او اي توكن :>

function getJSONData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $jsonData = curl_exec($ch);
    curl_close($ch);
    return $jsonData;
}

$jsonData = getJSONData('http://45.88.109.194:30120/players.json');
if ($jsonData === false) {
    echo 'لم يتم استيراد أسماء اللاعبين، يرجى التحقق من العنوان.';
    exit;
}


$players = json_decode($jsonData, true);
$playerCount = count($players);

echo '<style>
.players-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.player-card {
    width: 250px;
    margin: 10px;
    padding: 20px;
    background-color: #2c3e50;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
    transition: transform 0.3s ease;
	text-align: center
    cursor: pointer;
}

.player-card:hover {
    transform: scale(1.05);
}

.player-card::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    opacity: 0.5;
    z-index: -1;
    transition: opacity 0.3s ease;
}

.player-card:hover::before {
    opacity: 0.8;
}

.player-name {
    font-weight: bold;
    font-size: 18px;
    color: #fff;
    margin-bottom: 10px;
	text-align: center
}

.discord {
    font-size: 14px;
    color: #ccc;
	text-align: center
}

.players-wrapper {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background-color: #34495e;
    border-radius: 10px;
    transition: background-color 0.3s ease;
    font-family: Arial, sans-serif;
}

.players-title {
    font-weight: bold;
    font-size: 24px;
    text-align: center;
    margin-bottom: 20px;
    color: #fff;
}

.online-title {
    color: #fff;
    font-weight: bold;
    font-size: 24px;
    text-align: center;
    margin-bottom: 20px;
text-align: center
}

@media screen and (max-width: 768px) {
    .player-card {
        width: calc(50% - 20px);
    }
}

@media screen and (max-width: 480px) {
    .player-card {
        width: 100%;
    }
}


</style>';

echo '<div class="players-wrapper">';
echo '<div class="players-title">One-Life Online Players</div>';
echo '<div class="online-title">  ' . $playerCount . '</div>';

echo '<div class="players-container">';
$output = '';

foreach ($players as $player) {
    echo '<div class="player-card">';
    echo '<div class="player-name">' . $player['name'] . '</div>';
    $discordFound = false;
    foreach ($player['identifiers'] as $identifier) {
        if (strpos($identifier, 'discord:') !== false) {
            $discordId = str_replace('discord:', '', $identifier);
                $output .= '</div>';

            
            $apiUrl = "https://discord.com/api/v10/users/{$discordId}";
            
            $options = [
                'http' => [
                    'header' => "Authorization: Bot {$accessToken}\r\n"
                ]
            ];
            
            $context = stream_context_create($options);
            $response = file_get_contents($apiUrl, false, $context);
            
            if ($response === false) {
                echo 'حدث خطأ في استرداد بيانات الحساب.';
                exit;
            }
            
            $userData = json_decode($response, true);
            $discordUsername = $userData['username'];
            
            echo '<div class="discord">' . $discordUsername . '</div>';
            $discordFound = true;
            break;
        }
    }
    
    if (!$discordFound) {
        echo '<div class="discord">لا يوجد حساب دسكورد</div>';
    }
    
    echo '</div>';
}
    echo '</div>';


echo '</div>';
echo '</div>';

?>
